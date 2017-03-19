<?php

namespace Dipsycat\FbSocialBundle\Security;

use Dipsycat\FbSocialBundle\Entity\Role;
use Dipsycat\FbSocialBundle\Entity\User;
use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use KnpU\Guard\AbstractGuardAuthenticator;
use KnpU\Guard\Exception\CustomAuthenticationException;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Dipsycat\FbSocialBundle\Entity\Conversation;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookAuthenticator extends AbstractGuardAuthenticator {

    private $container;
    private $router;

    public function __construct(ContainerInterface $container, Router $router) {
        $this->container = $container;
        $this->router = $router;
    }

    public function start(Request $request, AuthenticationException $authException = null) {
    }

    public function getCredentials(Request $request) {

        return $request->query->get('code');
        if($request->getPathInfo() != '/connect/facebook') {
            return null;
        }

        if($request->query->get('code')) {
            return $request->query->get('code');
        }

        throw new CustomAuthenticationException();

    }

    public function getUser($credentials, UserProviderInterface $userProvider) {
        $facebookProvider = $this->container->get('app.facebook_provider');
        try {

            $accessToken = $facebookProvider->getAccessToken(
                'authorization_code',
                ['code' => $credentials]
            );

            $facebookUser = $facebookProvider->getResourceOwner($accessToken);
            $facebookId = $facebookUser->getId();
            $facebookName = $facebookUser->getFirstName();
            $facebookSurName = $facebookUser->getLastName();

            $em = $this->container->get('doctrine')->getManager();
            $UserRepository = $em->getRepository('DipsycatFbSocialBundle:User');
            $User = $UserRepository->findOneBy(array('facebookId' => $facebookId));
            if(empty($User)) {
                $User = new User();
                $User->setUsername($facebookName);
                $User->setSurname($facebookSurName);
                $User->setFacebookId($facebookId);
                $plainPassword = time();
                $salt = time() * time();
                $User->setSalt($salt);
                $encoder = $this->container->get('security.password_encoder');
                $encoded = $encoder->encodePassword($User, $plainPassword);
                $User->setPassword($encoded);

                $RoleRepository = $em->getRepository('DipsycatFbSocialBundle:Role');
                $Role = $RoleRepository->findOneBy(array('name' => 'ROLE_ADMIN'));
                $User->addUserRole($Role);

                $em->persist($User);
                $em->flush();
            }
            return $User;


        } catch (IdentityProviderException $e) {
            // probably the authorization code has been used already
            $response = $e->getResponseBody();
            $errorCode = $response['error']['code'];
            $message = $response['error']['message'];
            throw CustomAuthenticationException::createWithSafeMessage(
                'There was an error logging you into Facebook - code '.$errorCode
            );
        }
    }

    public function checkCredentials($credentials, UserInterface $user) {
        if(empty($credentials)) {
            throw new BadCredentialsException();
        }

    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        throw new BadCredentialsException();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {
        return new RedirectResponse($this->router->generate('dipsycat_fb_social_homepage'));
    }

    public function supportsRememberMe() {
    }


}

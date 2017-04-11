<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Dipsycat\FbSocialBundle\Entity\Message;
use Dipsycat\FbSocialBundle\Form\Type\RegistrationType;
use Dipsycat\FbSocialBundle\Service\Mailer;
use Dipsycat\FbSocialBundle\Service\MessageRegistration;
use IAkumaI\SphinxsearchBundle\Exception\EmptyIndexException;
use IAkumaI\SphinxsearchBundle\Exception\NoSphinxAPIException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dipsycat\FbSocialBundle\Entity\User;
use Dipsycat\FbSocialBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class UserController extends Controller {

    public function indexAction() {
        return $this->render('DipsycatFbSocialBundle:User:index.html.twig');
    }

    public function editAction() {
        return $this->render('DipsycatFbSocialBundle:User:edit.html.twig');
    }

    public function getUserFormAction(Request $request) {
        $user = $this->getUser();
        $form = $this->createForm(new UserType(), $user, [
            'action' => $this->generateUrl('dipsycat_fb_social_user_edit_post')
        ]);
        return $this->render('DipsycatFbSocialBundle:User:edit.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    public function postUserFormAction(Request $request) {
        $data = ['result' => 'error'];
        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $this->getUser();
            $form = $this->createForm(new UserType(), $user);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                
                $Uploader = $this->get('app.uploader');
                $fileName = $Uploader->uploadFile($user->getAvatar());
                $user->setAvatarPath($fileName);
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Your changes were saved!');
            } else {
                $this->addFlash('notice', 'This username is already used/ @todo');
            }
        }
        return $this->redirect($this->generateUrl('dipsycat_fb_social_user_edit'), 301);
    }

    public function registrationUserAction(Request $request) {
        $user = new User();
        $form = $this->createForm(new RegistrationType(), $user, [
            'action' => $this->generateUrl('dipsycat_fb_social_user_registration')
        ]);
        $form->handleRequest($request);
        $error = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $salt = hash('md5', time());
            $user->setSalt($salt);
            $password = $this->get('security.password_encoder')
                ->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $roleRepository = $em->getRepository('DipsycatFbSocialBundle:Role');
            $role = $roleRepository->findOneBy([
                'name' => 'ROLE_CONFIRM'
            ]);
            $user->addUserRole($role);
            $em->persist($user);
            $em->flush();
            $this->sendVerifyMessage($user);
            $this->addFlash(
                'notice',
                'Your account was registrated. Please see your email. Confirm it'
            );

            return $this->redirectToRoute('dipsycat_fb_social_user_confirm_email_page');
        } else {
            $error = $form->getErrors();
        }

        return $this->render('DipsycatFbSocialBundle:User:registration.html.twig', [
            'form' => $form->createView(),
            'error' => $error
        ]);
    }

    private function sendVerifyMessage($User) {
        $Mailer = $this->get('app.mailer');

        $data = [
            'name' => $User->getUsername(),
            'confirm_url' => $Mailer->createConfirmPath($User)
        ];

        $body = $this->renderView('emails/registration.html.twig', $data);
        $MessageRegistration = new MessageRegistration('registration', $body);

        return $Mailer->send($User, $MessageRegistration);
    }

    public function confirmPageAction() {
        return $this->render('DipsycatFbSocialBundle:User:confirm.html.twig');
    }

    public function confirmAction(Request $request) {
        $confirmUrl = $request->get('confirm_url');
        $Mailer = $this->get('app.mailer');
        if(!$Mailer->verifyUrl($confirmUrl)) {
            throw $this->createNotFoundException('Not found');
        }
        $id = $Mailer->verifyUrl($confirmUrl);

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('DipsycatFbSocialBundle:User');
        $User = $userRepository->find($id);
        $roleRepository = $em->getRepository('DipsycatFbSocialBundle:Role');
        $Role = $roleRepository->findOneBy([
            'name' => 'ROLE_ADMIN'
        ]);
        $User->addUserRole($Role);
        $em->persist($User);
        $em->flush();
        $this->addFlash(
            'notice',
            'Your account is confirmed'
        );

        return $this->redirectToRoute('_security_login');
    }

}

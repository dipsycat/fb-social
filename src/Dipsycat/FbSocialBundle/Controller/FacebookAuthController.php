<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Dipsycat\FbSocialBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class FacebookAuthController extends Controller {

    public function connectFacebookAction(Request $request)
    {
        // redirect to Facebook
        $facebookOAuthProvider = $this->get('app.facebook_provider');
        $url = $facebookOAuthProvider->getAuthorizationUrl([
            // these are actually the default scopes
            'scopes' => ['public_profile', 'email'],
        ]);
        return $this->redirect($url);
    }

    public function connectFacebookCheckAction()
    {
        // will not be reached!
    }

}

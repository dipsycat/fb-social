<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller {

    public function indexAction() {
        return $this->render('DipsycatFbSocialBundle:Main:index.html.twig');
    }

}

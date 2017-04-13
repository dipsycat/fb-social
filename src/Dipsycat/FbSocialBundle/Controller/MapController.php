<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MapController extends Controller {

    public function indexAction() {
        return $this->render('DipsycatFbSocialBundle:Map:index.html.twig');
    }

}

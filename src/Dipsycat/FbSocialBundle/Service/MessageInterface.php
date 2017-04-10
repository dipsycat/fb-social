<?php

namespace Dipsycat\FbSocialBundle\Service;


interface MessageInterface {
    public function getBody();
    public function getSubject();
    public function getType();
}

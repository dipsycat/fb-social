<?php

namespace Dipsycat\FbSocialBundle\Interfaces;


interface MessageInterface {
    public function getBody();
    public function getSubject();
    public function getType();
}

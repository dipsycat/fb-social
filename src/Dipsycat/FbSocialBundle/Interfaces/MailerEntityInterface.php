<?php

namespace Dipsycat\FbSocialBundle\Interfaces;


interface MailerEntityInterface {
    public function getId();
    public function getUsername();
    public function getEmail();
}

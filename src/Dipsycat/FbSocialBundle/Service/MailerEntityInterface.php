<?php

namespace Dipsycat\FbSocialBundle\Service;


interface MailerEntityInterface {
    public function getId();
    public function getUsername();
    public function getEmail();
}

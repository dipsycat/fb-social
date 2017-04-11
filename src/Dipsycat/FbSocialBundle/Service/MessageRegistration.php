<?php

namespace Dipsycat\FbSocialBundle\Service;


class MessageRegistration implements MessageInterface {

    private $body;
    private $subject;

    public function __construct($subject, $body) {
        $this->subject = $subject;
        $this->body = $body;
    }

    public function getBody() {
        return $this->body;
    }

    public function getType() {
        return 'text/html';
    }

    public function getSubject() {
        return $this->subject;
    }


}
<?php

namespace Dipsycat\FbSocialBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Dipsycat\FbSocialBundle\Interfaces\MailerEntityInterface;
use Dipsycat\FbSocialBundle\Interfaces\MessageInterface;

class Mailer {

    const MAILER_USER = 'mailer_user';
    private $container;
    private $mailer;

    private $emailFrom;

    public function __construct(ContainerInterface $container, $mailer) {
        $this->container = $container;
        $this->mailer = $mailer;
        $this->emailFrom = $this->container->getParameter(self::MAILER_USER);
    }
    
    public function send(MailerEntityInterface $entity, MessageInterface $emailMessage) {
        $logger = new \Swift_Plugins_Loggers_ArrayLogger();
        $this->mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));
        $emailMessage = $this->createEmailMessage($entity, $emailMessage);
        return $this->mailer->send($emailMessage);
    }

    public function createConfirmPath(MailerEntityInterface $entity) {
        $data = [
            'id' => $entity->getId(),
            'date' => time()
        ];
        $json = json_encode($data);
        return base64_encode($json);
    }

    public function createEmailMessage(MailerEntityInterface $entity, MessageInterface $message) {
        $message = \Swift_Message::newInstance()
            ->setSubject($message->getSubject())
            ->setFrom($this->emailFrom)
            ->setTo($entity->getEmail())
            ->setBody($message->getBody(),$message->getType())
        ;
        return $message;
    }

    public function verifyUrl($url) {
        $json = base64_decode($url);
        $data = json_decode($json);
        if(empty($data->id) || empty($data->date)) {
            return false;
        }
        if(time() - $data->date >= 86000) {
            return false;
        }
        return $data->id;
    }
}

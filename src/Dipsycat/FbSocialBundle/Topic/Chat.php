<?php

namespace Dipsycat\FbSocialBundle\Topic;

use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;
use Dipsycat\FbSocialBundle\Entity\Conversation;

class Chat implements TopicInterface {

    private $doctrine;
    private $token;
    private $clientStorage;

    public function __construct(
    \Doctrine\Bundle\DoctrineBundle\Registry $doctrine, \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $token, \Gos\Bundle\WebSocketBundle\Client\ClientStorageInterface $clientStorage) {
        $this->doctrine = $doctrine;
        $this->token = $token;
        $this->clientStorage = $clientStorage;
    }

    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request) {
        return;
    }

    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request) {
        return;
    }

    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible) {
        $conversationId = $request->getAttributes()->get('conversation');
        if(empty($conversationId)) {
            return;
        }
        $client = $this->clientStorage->getClient($connection->WAMP->clientStorageId);
        if (!is_object($client)) {
            return;
        }

        $Message = new \Dipsycat\FbSocialBundle\Entity\Message();
        $em = $this->doctrine->getManager();
        $ConversationRepository = $em->getRepository('DipsycatFbSocialBundle:Conversation');
        $Conversation = $ConversationRepository->find($conversationId);

        $UserRepository = $em->getRepository('DipsycatFbSocialBundle:User');
        $User = $UserRepository->find($client->getId());

        $Message->setUser($User);
        $Message->setConversation($Conversation);
        $Message->setText($event);

        $em->persist($Message);
        $em->flush();

        $message = [
            'text' => $event,
            'username' => $User->getUsername(),
            'created_at' => $Message->getCreatedAtAgo(),
            'is_my_message' => false
        ];
        $data = [
            'message' => $message,
        ];
        $excluded = [
            $connection->WAMP->sessionId
        ];
        $topic->broadcast($data, $excluded);

        $message['is_my_message'] = true;
        $data = [
            'message' => $message,
        ];
        $recipients = [
            $connection->WAMP->sessionId
        ];
        $topic->broadcast($data, [], $recipients);
    }

    public function getName() {
        return 'app.topic.chat';
    }

}

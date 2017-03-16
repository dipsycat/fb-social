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
        $room = $request->getAttributes()->get('room');
        $userId = $request->getAttributes()->get('user_id');

        $user = $this->token->getToken()->getUser();
        $em = $this->doctrine->getManager();
        $ConversationRepository = $em->getRepository('DipsycatFbSocialBundle:Conversation');
        $Converstaion = $ConversationRepository->find(1);
        $messages = [];
        foreach ($Converstaion->getMessages() as $message) {
            $isMyMessage = false;
            if ($message->getUser()->getId() == $user->getId()) {
                $isMyMessage = true;
            }
            $messages[] = [
                'text' => $message->getText(),
                'username' => $message->getUser()->getUsername(),
                'created_at' => $message->getCreatedAtAgo(),
                'is_my_message' => $isMyMessage
            ];
        }


        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast([
            //'msg' => 'Новый пользователь зашел в комнату ' . $room . ' в личку к пользователю ' . $userId,
            'msg' => '' . $room . ' в личку к пользователю ' . $userId,
            //'msg' => $Converstaion->getMessages()->toArray(),
            'messages' => $messages,
            'user' => $user->getUsername(),
            'message' => []
        ]);
    }

    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request) {
        $room = $request->getAttributes()->get('room');
        $userId = $request->getAttributes()->get('user_id');
        $topic->broadcast(['msg' => 'Новый пользователь вышел из комнаты ' . $room . ' лички с пользователем ' . $userId]);
    }

    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible) {
        $room = $request->getAttributes()->get('room');
        $userId = $request->getAttributes()->get('user_id');
        
        $em = $this->doctrine->getManager();
        $user = $this->token->getToken()->getUser();
        $Message = new \Dipsycat\FbSocialBundle\Entity\Message();
        $ConversationRepository = $em->getRepository('DipsycatFbSocialBundle:Conversation');
        $Conversation = $ConversationRepository->find(1);
        
        //file_put_contents('d:\1.txt', print_r($Conversation->getId(), true), FILE_APPEND);
        //file_put_contents('d:\1.txt', print_r($user->getId(), true), FILE_APPEND);
        //file_put_contents('d:\1.txt', print_r($Message->getId(), true), FILE_APPEND);
        $Message->setUser($user);
        $Message->setConversation($Conversation);
        $Message->setText($event);
        
        $em->persist($Message);
        $em->flush();
        
        
        $topic->broadcast([
            'msg' => 'В комнату ' . $room . 'пользователю ' . $userId . ' поступило сообщение: ' . $event,
            'message' => [
                'text' => $event,
                'username' => $user->getUsername(),
                'created_at' => $Message->getCreatedAtAgo(),
                'is_my_message' => true
            ],
        ]);
    }

    public function getName() {
        return 'app.topic.chat';
    }

}

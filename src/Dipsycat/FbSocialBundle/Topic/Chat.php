<?php

namespace Dipsycat\FbSocialBundle\Topic;

use Gos\Bundle\WebSocketBundle\Topic\TopicInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Gos\Bundle\WebSocketBundle\Router\WampRequest;

class Chat implements TopicInterface
{

    public function onSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $room = $request->getAttributes()->get('room');
        $userId = $request->getAttributes()->get('user_id');

        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast(['msg' => 'Новый пользователь зашел в комнату ' . $room . ' в личку к пользователю ' . $userId]);
    }

    public function onUnSubscribe(ConnectionInterface $connection, Topic $topic, WampRequest $request)
    {
        $room = $request->getAttributes()->get('room');
        $userId = $request->getAttributes()->get('user_id');
        //this will broadcast the message to ALL subscribers of this topic.
        $topic->broadcast(['msg' => 'Новый пользователь вышел из комнаты ' . $room . ' лички с пользователем ' . $userId]);
    }

    public function onPublish(ConnectionInterface $connection, Topic $topic, WampRequest $request, $event, array $exclude, array $eligible)
    {
        $room = $request->getAttributes()->get('room');
        $userId = $request->getAttributes()->get('user_id');

        $topic->broadcast([
            'msg' => 'В комнату ' . $room . 'пользователю ' . $userId . ' поступило сообщение: ' . $event,
        ]);
    }

    public function getName()
    {
        return 'app.topic.chat';
    }
}
<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ConversationController extends Controller
{
    public function indexAction()
    {
        return $this->render('DipsycatFbSocialBundle:Conversation:index.html.twig');
    }

    public function getConversationAction($id) {
        $user = $this->getUser();
        $userConversations = $user->getUserConversations();
        if(in_array($id, $userConversations->toArray())) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        $conversationRepository = $em->getRepository('DipsycatFbSocialBundle:Conversation');
        $conversation = $conversationRepository->find($id);
        if(empty($conversation)) {
            throw $this->createNotFoundException();
        }
        return $this->render('DipsycatFbSocialBundle:Conversation:conversation.html.twig',
            [
                'conversation' => $conversation
            ]
        );
    }
}

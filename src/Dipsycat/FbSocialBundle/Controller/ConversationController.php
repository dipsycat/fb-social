<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Dipsycat\FbSocialBundle\Entity\Message;
use Dipsycat\FbSocialBundle\Form\Type\MessageType;

class ConversationController extends Controller {

    public function indexAction() {
        return $this->render('DipsycatFbSocialBundle:Conversation:index.html.twig');
    }
    
    public function newAction() {
        return $this->render('DipsycatFbSocialBundle:Conversation:new.html.twig');
    }

    public function getConversationAction($id) {
        $user = $this->getUser();
        $userConversations = $user->getUserConversations();
        if (in_array($id, $userConversations->toArray())) {
            throw $this->createNotFoundException();
        }
        $em = $this->getDoctrine()->getManager();
        $conversationRepository = $em->getRepository('DipsycatFbSocialBundle:Conversation');
        $conversation = $conversationRepository->find($id);
        if (empty($conversation)) {
            throw $this->createNotFoundException();
        }
        return $this->render('DipsycatFbSocialBundle:Conversation:conversation.html.twig', [
                    'conversation' => $conversation
                        ]
        );
    }

    public function sendMessageAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();
        $conversationRepository = $em->getRepository('DipsycatFbSocialBundle:Conversation');
        $conversation = $conversationRepository->find($id);
        if (empty($conversation)) {
            throw $this->createNotFoundException();
        }
        if ($request->isMethod(Request::METHOD_POST)) {
            $message = new Message();
            $messageText = $request->request->get('text');
            if ($messageText) {
                $user = $this->getUser();
                $message->setUser($user);
                $message->setConversation($conversation);
                $message->setText($messageText);
                $em->persist($message);
                $em->flush();
            }
        }
        return $this->redirectToRoute('dipsycat_fb_social_conversation', array('id' => $conversation->getId()));
    }
}

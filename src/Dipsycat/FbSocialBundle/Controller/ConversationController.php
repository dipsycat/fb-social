<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Dipsycat\FbSocialBundle\Entity\Message;
use Dipsycat\FbSocialBundle\Form\Type\MessageType;
use Dipsycat\FbSocialBundle\Entity\Conversation;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConversationController extends Controller {

    public function indexAction() {
        return $this->render('DipsycatFbSocialBundle:Conversation:index.html.twig');
    }

    public function newConverstaionAction() {
        return $this->render('DipsycatFbSocialBundle:Conversation:new.html.twig');
    }

    public function newPostAction(Request $request) {
        if ($request->isMethod(Request::METHOD_POST)) {
            $conversationName = $request->request->get('name');
            $conversation = new Conversation();
            $conversation->setName($conversationName);
            $em = $this->getDoctrine()->getManager();
            $em->persist($conversation);

            $conversationMembers = $request->request->get('members');
            if (empty($conversationMembers)) {
                $conversationMembers = array();
            }
            $conversationMembers = array_merge($conversationMembers, [$this->getUser()->getId()]);
            $userRepository = $em->getRepository('DipsycatFbSocialBundle:User');

            foreach ($conversationMembers as $memberId) {
                $user = $userRepository->find($memberId);
                if (!empty($user)) {
                    $user->addUserConversation($conversation);
                    $em->persist($user);
                }
            }

            $em->flush();
        }
        return $this->redirectToRoute('dipsycat_fb_social_conversation_list');
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

    public function editMessageAction(Request $request, $id) {
        if (!$request->isMethod(Request::METHOD_POST)) {
            return;
        }
        $em = $this->getDoctrine()->getManager();
        $messageRepository = $em->getRepository('DipsycatFbSocialBundle:Message');
        $message = $messageRepository->find($id);
        $data = [
            'result' => 'success'
        ];
        if ($this->getUser()->getId() != $message->getUser()->getId()) {
            $data = [
                'result' => 'error'
            ];
            return new JsonResponse([
                'data' => $data
            ]);
        }
        $messageText = $request->get('new-message');
        if (empty($messageText)) {
            $data = [
                'result' => 'error'
            ];
            return new JsonResponse([
                'data' => $data
            ]);
        }
        $message->setText($messageText);
        $em->persist($message);
        $em->flush();
        $data['message'] = $message->getText();
        return new JsonResponse($data);
    }

}

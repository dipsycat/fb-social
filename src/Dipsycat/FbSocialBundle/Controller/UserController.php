<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dipsycat\FbSocialBundle\Entity\User;
use Dipsycat\FbSocialBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends Controller {

    public function indexAction() {
        return $this->render('DipsycatFbSocialBundle:User:index.html.twig');
    }

    public function editAction() {
        return $this->render('DipsycatFbSocialBundle:User:edit.html.twig');
    }

    public function getUserFormAction(Request $request) {
        $user = new User();
        $form = $this->createForm(new UserType(), $user, [
            'action' => $this->generateUrl('dipsycat_fb_social_user_edit_post')
        ]);
        return $this->render('DipsycatFbSocialBundle:User:edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function postUserFormAction(Request $request) {
        $data = ['result' => 'error'];
        if ($request->isMethod(Request::METHOD_POST)) {
            $user = $this->getUser();
            $form = $this->createForm(new UserType(), $user);
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Your changes were saved!');
            } else {
                $this->addFlash('notice', 'This username is already used/ @todo');
            }
        }
        return $this->redirect($this->generateUrl('dipsycat_fb_social_user_edit'), 301);
    }

    public function getFriendsAction() {
        $user = $this->getUser();
        $friends = $user->getFriendsWithMe()->toArray();
        $friends = array_merge($friends, $user->getMyFriends()->toArray());
        return $this->render('DipsycatFbSocialBundle:User:friends.html.twig', [
            'friends' => $friends
        ]);
    }

    public function removeFriendAction(Request $request, $id) {

        $data = [
            'result' => 'error'
        ];
        if ($request->isMethod(Request::METHOD_POST)) {
            return new JsonResponse($data);
        }


        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('DipsycatFbSocialBundle:User');
        $friend = $userRepository->find($id);
        $user = $this->getUser();
        $user->removeMyFriend($friend);
        $friend->removeMyFriend($user);
        $em->persist($user);
        $em->persist($friend);
        $em->flush();
        $data = [
            'result' => 'success'
        ];
        return new JsonResponse($data);


    }

    public function searchUsersAction(Request $request) {
        $searchText = $request->get('search_text');
        $sphinx = $this->get('iakumai.sphinxsearch.search');
        return $sphinx->search($searchText, array('IndexName'));
    }

}

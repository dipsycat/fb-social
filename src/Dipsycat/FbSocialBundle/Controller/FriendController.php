<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class FriendController extends Controller {

    public function getFriendsAction() {
        $user = $this->getUser();
        $friends = $user->getFriendsWithMe()->toArray();
        $friends = array_merge($friends, $user->getMyFriends()->toArray());
        return $this->render('DipsycatFbSocialBundle:Friend:friends.html.twig', [
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

}

<?php

namespace Dipsycat\FbSocialBundle\Controller;

use IAkumaI\SphinxsearchBundle\Exception\EmptyIndexException;
use IAkumaI\SphinxsearchBundle\Exception\NoSphinxAPIException;
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
                
                $Uploader = $this->get('app.uploader');
                $fileName = $Uploader->uploadFile($user->getAvatar());
                $user->setAvatarPath($fileName);
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

        $searchText = $request->query->get('search_text');
        $sphinx = $this->get('iakumai.sphinxsearch.search');
        $logger = $this->get('logger');
        $result = [
            'result' => 'success'
        ];
        try {
            $data = $sphinx->searchEx('*' . $searchText . '*', array('IndexName'));
        } catch (EmptyIndexException $e) {
            $logger->info('Sphinx index is empty');
            $result['data'] = $this->searchUsers($searchText);
            return new JsonResponse($result);

        } catch (NoSphinxAPIException $e) {
            $logger->info('No sphinx Api');
            $result['data'] = $this->searchUsers($searchText);
            return new JsonResponse($result);

        } catch (\RuntimeException $e) {
            $logger->info('Error sphinx. Runtime Exception');
            $result['data'] = $this->searchUsers($searchText);
            return new JsonResponse($result);
        }

        if (empty($data['matches'])) {
            $result = [
                'result' => 'error'
            ];
            return new JsonResponse($result);
        }
        foreach ($data['matches'] as $user) {
            $entity = $user['entity'];
            $result['data'][$entity->getId()] = $entity->getUsername();
        }
        return new JsonResponse($result);
    }

    private function searchUsers($searchText = '') {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('DipsycatFbSocialBundle:User');
        $users = $userRepository->searchUsers($searchText);
        $result = [];
        foreach ($users as $user) {
            $result[$user->getId()] = $user->getUsername();
        }
        return $result;
    }

}

<?php

namespace Dipsycat\FbSocialBundle\Controller;

use IAkumaI\SphinxsearchBundle\Exception\EmptyIndexException;
use IAkumaI\SphinxsearchBundle\Exception\NoSphinxAPIException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SearchController extends Controller {


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

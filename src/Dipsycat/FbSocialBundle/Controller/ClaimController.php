<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Dipsycat\FbSocialBundle\Entity\Claim;
use Dipsycat\FbSocialBundle\Form\Type\ClaimType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ClaimController extends Controller {

    public function indexAction(Request $request) {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $claimRepository = $em->getRepository('DipsycatFbSocialBundle:Claim');
        $claim = $claimRepository->find($id);
        if (empty($claim)) {
            throw $this->createNotFoundException();
        }
        $form = $this->createForm(new ClaimType(), $claim, [
            'action' => $this->generateUrl('dipsycat_fb_social_claim', [
                'id' => $claim->getId()
            ])
        ]);

        $errors = array();
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            $validator = $this->get('validator');
            $errors = $validator->validate($claim);
            if ($form->isSubmitted() && $form->isValid() && count($errors) == 0) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($claim);
                $em->flush();
                $this->addFlash('notice', 'Your changes were saved!');
            }
        }

        return $this->render('DipsycatFbSocialBundle:Claim:index.html.twig', [
            'claim' => $claim,
            'form' => $form->createView()
        ]);
    }

    public function listAction() {

        $em = $this->getDoctrine()->getManager();
        $claimRepository = $em->getRepository('DipsycatFbSocialBundle:Claim');
        $claims = $claimRepository->findAll();
        return $this->render('DipsycatFbSocialBundle:Claim:list.html.twig', [
            'claims' => $claims,
        ]);
    }

}

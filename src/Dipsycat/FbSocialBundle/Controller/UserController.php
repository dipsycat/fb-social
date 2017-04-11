<?php

namespace Dipsycat\FbSocialBundle\Controller;

use Dipsycat\FbSocialBundle\Form\Type\RegistrationType;
use Dipsycat\FbSocialBundle\Classes\MessageRegistration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dipsycat\FbSocialBundle\Entity\User;
use Dipsycat\FbSocialBundle\Form\Type\UserType;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller {

    public function userEditAction(Request $request) {
        $user = $this->getUser();
        $form = $this->createForm(new UserType(), $user, [
            'action' => $this->generateUrl('dipsycat_fb_social_user_edit')
        ]);
        $errors = array();
        if ($request->isMethod(Request::METHOD_POST)) {
            $form = $this->createForm(new UserType(), $user);
            $form->handleRequest($request);
            $validator = $this->get('validator');
            $errors = $validator->validate($user);
            if ($form->isSubmitted() && $form->isValid() && count($errors) == 0) {
                $em = $this->getDoctrine()->getManager();
                if (!empty($user->getAvatar())) {
                    $Uploader = $this->get('app.uploader');
                    $fileName = $Uploader->uploadFile($user->getAvatar());
                    $user->setAvatarPath($fileName);
                }
                $em->persist($user);
                $em->flush();
                $this->addFlash('notice', 'Your changes were saved!');
            }
        }

        return $this->render('DipsycatFbSocialBundle:User:edit.html.twig', [
                    'form' => $form->createView(),
                    'errors' => $errors
        ]);
    }

    public function registrationUserAction(Request $request) {
        $user = new User();
        $form = $this->createForm(new RegistrationType(), $user, [
            'action' => $this->generateUrl('dipsycat_fb_social_user_registration')
        ]);
        if ($request->isMethod(Request::METHOD_POST)) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid() && $this->isValidateEntity($user)) {
                $em = $this->getDoctrine()->getManager();
                $salt = hash('md5', time());
                $user->setSalt($salt);
                $password = $this->get('security.password_encoder')
                        ->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);
                $roleRepository = $em->getRepository('DipsycatFbSocialBundle:Role');
                $role = $roleRepository->findOneBy([
                    'name' => 'ROLE_CONFIRM'
                ]);
                $user->addUserRole($role);
                $em->persist($user);
                $em->flush();
                $this->sendVerifyMessage($user);

                return $this->redirectToRoute('dipsycat_fb_social_user_confirm_email_page');
            }
        }

        return $this->render('DipsycatFbSocialBundle:User:registration.html.twig', [
                    'form' => $form->createView()
        ]);
    }

    private function isValidateEntity($entity) {
        $validator = $this->get('validator');
        $errors = $validator->validate($entity);
        return count($errors) > 0 ? false : true;
    }

    private function sendVerifyMessage($User) {
        $Mailer = $this->get('app.mailer');

        $data = [
            'name' => $User->getUsername(),
            'confirm_url' => $Mailer->createConfirmPath($User)
        ];

        $body = $this->renderView('emails/registration.html.twig', $data);
        $MessageRegistration = new MessageRegistration('registration', $body);

        return $Mailer->send($User, $MessageRegistration);
    }

    public function confirmPageAction() {
        return $this->render('DipsycatFbSocialBundle:User:confirm.html.twig');
    }

    public function confirmAction(Request $request) {
        $confirmUrl = $request->get('confirm_url');
        $Mailer = $this->get('app.mailer');
        if (!$Mailer->verifyUrl($confirmUrl)) {
            throw $this->createNotFoundException('Not found');
        }
        $id = $Mailer->verifyUrl($confirmUrl);

        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository('DipsycatFbSocialBundle:User');
        $User = $userRepository->find($id);
        $roleRepository = $em->getRepository('DipsycatFbSocialBundle:Role');
        $Role = $roleRepository->findOneBy([
            'name' => 'ROLE_ADMIN'
        ]);
        if ($User->hasRole($Role)) {
            $this->addFlash(
                    'notice', 'Your account was confirmed'
            );
            return $this->redirectToRoute('_security_login');
        }

        $User->addUserRole($Role);
        $em->persist($User);
        $em->flush();
        $this->addFlash(
                'notice', 'Your account is confirmed'
        );

        return $this->redirectToRoute('_security_login');
    }

}

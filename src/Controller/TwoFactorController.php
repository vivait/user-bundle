<?php

namespace Vivait\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Vivait\UserBundle\Entity\User;

class TwoFactorController extends Controller
{

    public function twoFactorAction(Request $request)
    {
        $user = $this->getUser();

        $form_generator = $this->createForm('two_factor_generator');
        $form_generator->handleRequest($request);

        $form_disable = $this->createForm('two_factor_disable');
        $form_disable->handleRequest($request);

        $session = $this->get('session');

        if ($form_generator->isValid()) {
            $secret = $this->container->get("scheb_two_factor.security.google_authenticator")->generateSecret();
            $this->updateTwoFactor($secret, $user);

            $session->getFlashBag()->add('success', "Two-factor settings saved");
            return $this->redirect($this->generateUrl('vivait_user_security_two_factor'));
        }

        if($form_disable->isValid()){
            $this->updateTwoFactor(null, $user);

            $session->getFlashBag()->add('success', "Two-factor settings saved");
            return $this->redirect($this->generateUrl('vivait_user_security_two_factor'));
        }

        return $this->render(
            'VivaitUserBundle:Profile/Security:two_factor.html.twig',
            [
                'two_factor' => [
                    'code' => $this->getUser()->getGoogleAuthenticatorSecret(),
                    'qr' => $this->container->get("scheb_two_factor.security.google_authenticator")->getUrl($user),
                ],
                'form' => [
                    'generate' => $form_generator->createView(),
                    'disable' => $form_disable->createView(),
                ],
            ]
        );
    }

    /**
     * @param $user
     */
    private function updateTwoFactor($secret, User $user)
    {
        $user->setGoogleAuthenticatorSecret($secret);
        $user_manager = $this->container->get('fos_user.user_manager');
        $user_manager->updateUser($user);
    }


}

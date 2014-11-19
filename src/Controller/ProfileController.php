<?php

namespace Vivait\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    public function securityAction()
    {
        return $this->render(
            'VivaitUserBundle:Profile/Security:security.html.twig',
            [
                'two_factor' => [
                    'code' => (bool) $this->getUser()->getGoogleAuthenticatorSecret(),
                ]
            ]
        );
    }
}

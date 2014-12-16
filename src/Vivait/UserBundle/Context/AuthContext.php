<?php

namespace Vivait\UserBundle\Context;

use Behat\Mink\Exception\UnsupportedDriverActionException;
use Behat\Symfony2Extension\Context\KernelDictionary;
use SensioLabs\Behat\PageObjectExtension\Context\PageObjectContext;
use Vivait\UserBundle\Context\Dictionary\UserDictionary;
use Vivait\Page\Login;
use Vivait\UserBundle\Model\BaseUser;

class AuthContext extends PageObjectContext
{
    use KernelDictionary;
    use UserDictionary;

    /**
     * @Given I am logged in as :user using the password :password
     * @param BaseUser $user
     * @param $password
     * @throws \Exception
     */
    public function iAmLoggedInAsUsingThePassword(BaseUser $user, $password)
    {
        $session = $this->getContainer()->get('session');
        $firewall = $this->getContainer()->getParameter('fos_user.firewall_name');
        $page = $this->getPage('Login');

        try{
            /* @var $page Login */
            $page->login($user, $session, $firewall);
        }catch(UnsupportedDriverActionException $e){
            $this->iAmManuallyLoggedInAsUsingThePassword($user, $password);
        }

        return $user;
    }

    /**
     * @Given I am manually logged in as :user using the password :password
     */
    public function iAmManuallyLoggedInAsUsingThePassword(BaseUser $user, $password)
    {
        /* @var $page Login */
        $page = $this->getPage('Login')->open();
        $page->longLogin($user, $password);
    }

}
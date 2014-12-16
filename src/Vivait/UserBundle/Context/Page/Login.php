<?php

namespace Vivait\UserBundle\Context\Page;

use Behat\Mink\Driver\BrowserKitDriver;
use Behat\Mink\Exception\UnsupportedDriverActionException;
use SensioLabs\Behat\PageObjectExtension\PageObject\Exception\ElementNotFoundException;
use SensioLabs\Behat\PageObjectExtension\PageObject\Page;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Vivait\UserBundle\Model\BaseUser;

class Login extends Page
{
    protected $path = '/login';

    protected $elements = [
        'form.login' => 'form#login',
        'errors' => '#errors',
        'header.two.factor' => 'h1.form-header'
    ];

    public function requiresTwoFactor(){
        $header = $this->getElement('header.two.factor');
        return $header->getText();
    }

    /**
     * @param BaseUser $user
     * @param SessionInterface $session
     * @param $firewall
     * @throws UnsupportedDriverActionException
     */
    public function login(BaseUser $user, SessionInterface $session, $firewall)
    {
        $driver = $this->getDriver();

        if (!$driver instanceof BrowserKitDriver) {
            //Fall back to manual login if BrowserKitDriver is not used
            throw new UnsupportedDriverActionException("Not supported by the current driver", $driver);
        }

        $client = $driver->getClient();
        $client->getCookieJar()->set( new Cookie( session_name(), true ) );

        $token = new UsernamePasswordToken( $user, null, $firewall, $user->getRoles() );
        $session->set( '_security_' . $firewall, serialize( $token ) );
        $session->save();

        $cookie = new Cookie( $session->getName(), $session->getId() );
        $client->getCookieJar()->set( $cookie );
    }

    public function longLogin(BaseUser $user, $password)
    {
        $login_form = $this->getElement('form.login');
        $login_form->fillField('_username', $user->getUsername());
        $login_form->fillField('_password', $password);
        $login_form->pressButton('Login');
    }

    public function errorsExist()
    {
        try {
            $this->getElement('errors');

            return true;
        } catch (ElementNotFoundException $e) {
            return false;
        }
    }
} 
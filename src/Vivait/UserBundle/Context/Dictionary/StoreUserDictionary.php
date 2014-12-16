<?php

namespace Vivait\UserBundle\Context\Dictionary;

use Behat\Behat\Hook\Scope\AfterStepScope;
use Vivait\UserBundle\Model\BaseUser;

trait StoreUserDictionary
{
    /**
     * @var BaseUser
     */
    public $user;

    /**
     * @AfterStep I am logged in as
     */
    public function storeUser(AfterStepScope $afterStepScope) {
        $this->user = $afterStepScope->getTestResult()->getCallResult()->getReturn();
    }
} 
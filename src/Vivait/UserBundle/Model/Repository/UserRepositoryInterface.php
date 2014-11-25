<?php

namespace Vivait\UserBundle\Model\Repository;

use FOS\UserBundle\Model\User;

interface UserRepositoryInterface
{
    /**
     * @return User[]
     */
    public function getAllUsers();
}

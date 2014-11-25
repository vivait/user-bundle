<?php

namespace Vivait\UserBundle\Model\Repository;

use Vivait\UserBundle\Model\User;

class UserRepository extends UserRepositoryAbstract implements UserRepositoryInterface
{

    /**
     * @return User[]
     */
    public function getAllUsers()
    {
        return $this->_em->createQueryBuilder()
                         ->select('u')
                         ->from('Vivait\UserBundle\Model\User', 'u', 'u.username')
                         ->getQuery()
                         ->getResult();
    }
}

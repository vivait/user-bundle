<?php

namespace Vivait\UserBundle\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Vivait\UserBundle\Model\BaseUser;

class BaseUserRepository extends EntityRepository
{
    /**
     * @return BaseUser[]
     */
    public function getAllUsers()
    {
        return $this->_em->createQueryBuilder()
                         ->select('u')
                         ->from('Vivait\UserBundle\Model\BaseUser', 'u', 'u.username')
                         ->getQuery()
                         ->getResult();
    }
}

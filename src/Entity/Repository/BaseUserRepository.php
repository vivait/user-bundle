<?php

namespace Vivait\UserBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Vivait\UserBundle\Entity\BaseUser;

class BaseUserRepository extends EntityRepository
{
    /**
     * @return BaseUser[]
     */
    public function getAllUsers()
    {
        return $this->_em->createQueryBuilder()
            ->select('u')
            ->from('VivaitUserBundle:BaseUser', 'u', 'u.username')
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace Vivait\UserBundle\Model\Repository;

use Vivait\UserBundle\Model\Staff;

class StaffRepository extends UserRepositoryAbstract implements UserRepositoryInterface
{
    /**
     * @return Staff[]
     */
    public function getAllUsers()
    {
        return $this->_em->createQueryBuilder()
                         ->select('u')
                         ->from('Vivait\UserBundle\Model\Staff', 'u', 'u.username')
                         ->getQuery()
                         ->getResult();
    }
}

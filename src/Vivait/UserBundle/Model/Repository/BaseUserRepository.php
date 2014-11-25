<?php

namespace Vivait\UserBundle\Model\Repository;

use Doctrine\ORM\EntityRepository;
use Vivait\UserBundle\Model\BaseUser;

class BaseUserRepository extends EntityRepository implements UserRepositoryInterface
{
    /**
     * @return BaseUser[]
     */
    public function getAllUsers()
    {
        $users = [];

        foreach ($this->getClassMetadata()->subClasses as $subClass) {
            /** @var UserRepositoryInterface $repo */
            $repo = $this->_em->getRepository($subClass);

            $users += $repo->getAllUsers();
        }

        return $users;
    }
}

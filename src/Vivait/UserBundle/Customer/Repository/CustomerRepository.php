<?php

namespace Vivait\UserBundle\Customer\Repository;

use Doctrine\ORM\EntityRepository;
use Vivait\CustomerBundle\Model\Customer;
use Vivait\UserBundle\Customer\Customer as UserCustomer;
use Vivait\UserBundle\Model\Repository\UserRepositoryInterface;

class CustomerRepository extends EntityRepository implements UserRepositoryInterface
{
    public function findByUniqueAttributes($identifer)
    {
        if (null != ($customer = $this->findOneBy(['id' => $identifer]))) {
            return $customer;
        } elseif (null != ($customer = $this->findOneBy(['username' => $identifer]))) {
            return $customer;
        } elseif (null != ($customer = $this->findOneBy(['email' => $identifer]))) {
            return $customer;
        } else {
            return null;
        }
    }

    public function getAllUsers()
    {
//        $rsm = new ResultSetMappingBuilder($this->_em);
//        $rsm->addEntityResult('Vivait\UserBundle\Customer\Customer', 'uc');
////        $rsm->addJoinedEntityFromClassMetadata('Vivait\CustomerBundle\Model\Customer', 'c', 'uc', 'customer');
//        $rsm->addFieldResult('uc', 'username_11', 'username');

        $query = $this->_em->createQueryBuilder()
            # ->select('NEW Vivait\UserBundle\Customer\Customer(c)')
                           ->select('uc, c')
                           ->from('Vivait\CustomerBundle\Model\Customer', 'c', 'c.id')
                           ->leftJoin('Vivait\UserBundle\Customer\Customer', 'uc', 'WITH', 'uc.customer = c');

        $customers = $query
            ->getQuery()
            ->getResult();

        /** @var UserCustomer[] $users */
        $users = [];

        /** @var Customer[] $customers */
        foreach ($customers as $customer) {
            if ($customer instanceOf Customer) {
                $customer = new UserCustomer($customer);

                // Given how Doctrine currently works, this shouldn't happen
                if (isset($users[$customer->getUsername()])) {
                    continue;
                }
            } else if ($customer === null) {
                continue;
            }

            $users[$customer->getUsername()] = $customer;
        }

        return $users;
    }

    /**
     * Gets a list of users with the fields specified, indexed by their ID
     * The order first field is used for ordering
     *
     * @param array $fields
     * @return array
     */
    public function getUserList(array $fields = ['username'])
    {
        $mapped_fields = array_map(
            function ($field) {
                return 'u.' . $field;
            },
            $fields
        );

        return $this->_em->createQueryBuilder()
                         ->select('u.id')
                         ->addSelect($mapped_fields)
            // Index by the ID
                         ->from($this->_entityName, 'u', 'u.id')
                         ->orderBy($mapped_fields ? reset($mapped_fields) : 'u.username')
                         ->where('u.enabled = 1')
                         ->getQuery()
                         ->getResult('ListHydrator');
    }
}

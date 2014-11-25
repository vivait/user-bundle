<?php

namespace Vivait\UserBundle\Customer\Repository;

use Vivait\CustomerBundle\Model\Customer;
use Vivait\UserBundle\Customer\Customer as UserCustomer;
use Vivait\UserBundle\Model\Repository\UserRepositoryAbstract;
use Vivait\UserBundle\Model\Repository\UserRepositoryInterface;

class CustomerRepository extends UserRepositoryAbstract implements UserRepositoryInterface
{
    /**
     * @return UserCustomer[]
     */
    public function getAllUsers()
    {
        $query = $this->_em->createQueryBuilder()
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
}

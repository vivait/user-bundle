<?php

namespace Vivait\UserBundle\Customer;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Vivait\CustomerBundle\Model\Customer as FullCustomer;
use Vivait\CustomerBundle\Model\Email;
use Vivait\UserBundle\Model\BaseUser;

/**
 * @ORM\Entity(repositoryClass="Vivait\UserBundle\Model\Repository\UserRepository")
 * @ORM\Table(name="user_customer")
 */
class Customer extends BaseUser
{
    /**
     * @var FullCustomer
     *
     * @ORM\OneToOne(targetEntity="Vivait\CustomerBundle\Model\Customer")
     */
    private $customer;

    public function setFullname($fullname)
    {
        var_dump($fullname);
        throw new \LogicException('Customer names can\'t be changed via this method');
    }

    public function getFullname()
    {
        return (string) $this->customer->getName();
    }

    public function setEmail($email)
    {
        return $this->customer->setEmail(new Email($email));
    }

    public function getEmail()
    {
        return $this->customer->getEmail();
    }
}

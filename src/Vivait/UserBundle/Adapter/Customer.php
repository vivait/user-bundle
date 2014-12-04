<?php

namespace Vivait\UserBundle\Adapter;

use Doctrine\ORM\Mapping as ORM;
use Vivait\CustomerBundle\Model\Customer as FullCustomer;
use Vivait\CustomerBundle\Model\Email;
use Vivait\CustomerBundle\Model\Name;
use Vivait\UserBundle\Model\BaseUser;

/**
 * @ORM\Entity(repositoryClass="Vivait\UserBundle\Adapter\Repository\CustomerRepository")
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

    function __construct(FullCustomer $customer = null)
    {
        parent::__construct();
        $this->password = '';

        if ($customer) {
            $this->setCustomer($customer);
        }
    }

    /**
     * Gets customer
     * @return FullCustomer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Sets customer
     * @param FullCustomer $customer
     * @return $this
     */
    public function setCustomer(FullCustomer $customer)
    {
        $this->customer = $customer;
        $this->setUsername(
            $customer->getReference() ? '#' . $customer->getReference() : preg_replace(
                '/[^[:alnum:]]/u',
                '',
                (string) $customer->getName()
            )
        );
        $this->email = (string) $customer->getEmail();

        return $this;
    }

    public function setFullname($fullname)
    {
        $name = new \HumanNameParser_Parser($fullname);

        $this->customer->setName(new Name($name->getFirst(), $name->getLast(), $name->getMiddle()));

        return $this;
    }

    public function getFullname()
    {
        return (string) $this->customer->getName();
    }

    public function setEmail($email)
    {
        $this->customer->setEmail(new Email($email));
        $this->email = $email;

        return $this;
    }

    public function getEmail()
    {
        return $this->customer->getEmail();
    }
}

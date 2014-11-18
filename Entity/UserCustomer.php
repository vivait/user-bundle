<?php


namespace Vivait\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Vivait\CustomerBundle\Entity\Customer;
use Vivait\CustomerBundle\Entity\Email;

/**
 * @ORM\Entity(repositoryClass="Vivait\UserBundle\Entity\Repository\UserCustomerRepository")
 * @ORM\Table(name="user_customer")
 */
class UserCustomer extends BaseUser
{
    /**
     * @var Customer
     *
     * @ORM\OneToOne(targetEntity="Vivait\CustomerBundle\Entity\Customer")
     */
    private $customer;

    public function setFullname($fullname)
    {
        throw new \LogicException('Customer names can\'t be changed via this method');
    }

    public function getFullname()
    {
        return (string)$this->customer->getName();
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

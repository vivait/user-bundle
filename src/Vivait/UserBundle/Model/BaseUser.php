<?php

namespace Vivait\UserBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as UserModel;
use Scheb\TwoFactorBundle\Model\Google\TwoFactorInterface;

/**
 * @ORM\Entity(repositoryClass="Vivait\UserBundle\Model\Repository\BaseUserRepository")
 * @ORM\Table(name="base_user")
 * @ORM\InheritanceType("JOINED")
 */
abstract class BaseUser extends UserModel implements TwoFactorInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Vivait\UserBundle\Model\Group")
     * @ORM\JoinTable(name="fos_user_user_group",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $googleAuthenticatorSecret;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get groups
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * Return the Google Authenticator code
     * When an empty string or null is returned, the Google authentication is disabled.
     *
     * @return integer
     */
    public function getGoogleAuthenticatorSecret()
    {
        return $this->googleAuthenticatorSecret;
    }

    /**
     * Set the Google Authenticator code
     *
     * @param integer $googleAuthenticatorSecret
     */
    public function setGoogleAuthenticatorSecret($googleAuthenticatorSecret)
    {
        $this->googleAuthenticatorSecret = $googleAuthenticatorSecret;
    }

    /**
     * @param string $fullname
     * @return $this
     */
    abstract public function setFullname($fullname);

    /**
     * @return string
     */
    abstract public function getFullname();
}

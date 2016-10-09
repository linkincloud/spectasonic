<?php

namespace Spectasonic\Front\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\User as BaseUser;
use Spectasonic\Back\ShopManagerBundle\Entity\User_contract;
use Gedmo\Mapping\Annotation as Gedmo;
use Spectasonic\Back\UserManagerBundle\Entity\Vendor;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user") 
 * @ORM\Entity(repositoryClass="Spectasonic\Front\UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MODERATEUR = 'ROLE_MODERATEUR';
    const ROLE_VENDEUR = 'ROLE_VENDEUR';
    const ROLE_USER = 'ROLE_USER';
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     *
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="users_groups",
     *      joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    protected $groups;
    
    /**
     * @Gedmo\Slug(fields={"society"})
     * @ORM\Column(name="society", type="string", length=255, unique=true, nullable=true)
     */
    protected $society;

    /**
     * @var
     *
     * @ORM\Column(name="gender", type="string", length=255, nullable=true)
     */
    protected $gender;
    
    /**
     * @var
     *
     * @ORM\Column(name="firstname", type="string", length=255, nullable=true)
     */
    protected $firstname;

    /**
     * @var
     *
     * @ORM\Column(name="lastname", type="string", length=255, nullable=true)
     */
    protected $lastname;
    
    /**
     * @var
     *
     * @ORM\Column(name="user_display", type="string", length=255, nullable=true)
     */
    protected $user_display;
    
    /**
     * @var
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;
    
    /**
     * @var
     * 
     * @ORM\OneToMany(targetEntity="Spectasonic\Front\UserBundle\Entity\Address", mappedBy="user")
     * 
     */
    private $address;
    
    /**
     *
     * @ORM\OneToMany(targetEntity="Spectasonic\Back\ShopManagerBundle\Entity\User_contract", mappedBy="user")
     */
    protected $usercontract;
      
    /**
     *
     * @ORM\OneToOne(targetEntity="Spectasonic\Back\UserManagerBundle\Entity\Vendor", cascade={"persist", "remove"})
     */
    protected  $more;


    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->groups = new ArrayCollection();
        $this->address = new ArrayCollection();
        $this->usercontract = new ArrayCollection();
        
        
    }
    
    /**
     * @param mixed $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * @return mixed
     */
    public function getGender()
    {
        return $this->gender;
    }
    
    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
    
    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Add address
     *
     * @param \Spectasonic\Front\UserBundle\Address $address
     *
     * @return User
     */
    public function addAddress(Address $address)
    {
        $this->address[] = $address;
        
        //On lie l'user à l'adresse
        $address->setUser($this);

        return $this;
    }

    /**
     * Remove address
     *
     * @param \Spectasonic\Front\UserBundle\Address $address
     */
    public function removeAddress(Address $address)
    {
        $this->address->removeElement($address);
    }

    /**
     * Get address
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return User
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Add usercontract
     *
     * @param \Spectasonic\Back\ShopManagerBundle\Entity\User_contract $usercontract
     *
     * @return User
     */
    public function addUsercontract(User_contract $usercontract)
    {
        $this->usercontract[] = $usercontract;

        return $this;
    }

    /**
     * Remove usercontract
     *
     * @param \Spectasonic\Back\ShopManagerBundle\Entity\User_contract $usercontract
     */
    public function removeUsercontract(User_contract $usercontract)
    {
        $this->usercontract->removeElement($usercontract);
    }

    /**
     * Get usercontract
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsercontract()
    {
        return $this->usercontract;
    }

    /**
     * Set userDisplay
     *
     * @param string $userDisplay
     *
     * @return User
     */
    public function setUserDisplay($userDisplay)
    {
        $this->user_display = $userDisplay;

        return $this;
    }

    /**
     * Get userDisplay
     *
     * @return string
     */
    public function getUserDisplay()
    {
        return $this->user_display;
    }

    /**
     * Set society
     *
     * @param string $society
     *
     * @return User
     */
    public function setSociety($society)
    {
        $this->society = $society;

        return $this;
    }

    /**
     * Get society
     *
     * @return string
     */
    public function getSociety()
    {
        return $this->society;
    }

    /**
     * Set more
     *
     * @param Vendor $more
     *
     * @return User
     */
    public function setMore(Vendor $more = null)
    {
        $this->more = $more;

        return $this;
    }

    /**
     * Get more
     *
     * @return Vendor
     */
    public function getMore()
    {
        return $this->more;
    }
}

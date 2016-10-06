<?php

namespace Spectasonic\Back\ShopManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spectasonic\Front\UserBundle\Entity\User;
use Spectasonic\Back\ShopManagerBundle\Entity\Contract;
use Spectasonic\Back\ShopManagerBundle\Entity\ShopProduct;

/**
 * User_contract
 *
 * @ORM\Table(name="user_contract")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\ShopManagerBundle\Repository\User_contractRepository")
 */
class User_contract
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime")
     */
    private $updated;

    /**
     * @ORM\ManyToOne(targetEntity="Contract", inversedBy="usercontract", cascade={"persist"})
     * @ORM\JoinColumn(name="contract_id", referencedColumnName="id")
     */
    protected $contract;
    
    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Front\UserBundle\Entity\User", inversedBy="usercontract")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="ShopProduct", inversedBy="usercontract")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;
        
   
    public function __construct() {
        $this->created = new \DateTime();
        $this->updated = new \DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return User_contract
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return User_contract
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     *
     * @return User_contract
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set contract
     *
     * @param \Spectasonic\Back\ShopManagerBundle\Entity\Contract $contract
     *
     * @return User_contract
     */
    public function setContract(Contract $contract = null)
    {
        $this->contract = $contract;

        return $this;
    }

    /**
     * Get contract
     *
     * @return \Spectasonic\Back\ShopManagerBundle\Entity\Contract
     */
    public function getContract()
    {
        return $this->contract;
    }

    /**
     * Set user from Spectasonic\Front\UserBundle\Entity\User
     *
     */
    public function setUser(User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Spectasonic\Front\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product
     *
     * @param \Spectasonic\Back\ShopManagerBundle\Entity\ShopProduct $product
     *
     * @return User_contract
     */
    public function setProduct(ShopProduct $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \Spectasonic\Back\ShopManagerBundle\Entity\ShopProduct
     */
    public function getProduct()
    {
        return $this->product;
    }
}

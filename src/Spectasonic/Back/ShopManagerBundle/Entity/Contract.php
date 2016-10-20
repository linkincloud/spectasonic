<?php

namespace Spectasonic\Back\ShopManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection; 
use Spectasonic\Back\ShopManagerBundle\Entity\User_contract;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Contract
 *
 * @ORM\Table(name="contract")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\ShopManagerBundle\Repository\ContractRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Contract
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
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     */
    private $reference;

    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Front\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_contract", referencedColumnName="id")
     */
    private $author;


    /**
     *
     * @ORM\OneToMany(targetEntity="User_contract", mappedBy="contract")
     */
    protected $usercontract;
    
    public function __construct() {
        $this->usercontract = new ArrayCollection();
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
     * @param string $author
     * @return Contract
     */
    public function setAuthor($author) {
        $this->author = $author;
        return $this;
    }
    /**
     * @return string
     */
    public function getAuthor() {
        return $this->author;
    }
    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Contract
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Add usercontract
     *
     * @param \Spectasonic\Back\ShopManagerBundle\Entity\User_contract $usercontract
     *
     * @return Contract
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
}

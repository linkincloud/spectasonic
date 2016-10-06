<?php

namespace Spectasonic\Back\ShopManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use Spectasonic\Back\BlogBundle\Entity\Slider;
use Spectasonic\Back\ShopManagerBundle\Entity\User_contract;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\ShopManagerBundle\Repository\ShopProductRepository")
 * @UniqueEntity(fields="name", message="A product is already created with this name.");
 * @ORM\HasLifecycleCallbacks()
 * 
 *
 */
class ShopProduct {

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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\Length(min=10, minMessage="Ce champ doit faire au moins {{ limit }} caractères.")
     */
    private $name;

    /**
     * @ORM\Column(name="excerpt", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $excerpt;

    /**
     * @ORM\Column(name="content", type="text")
     * @Assert\NotBlank()
     */
    private $content;
    
    /**
     * @ORM\Column(name="actived", type="boolean")
     */
    private $actived = true;

    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Back\ShopManagerBundle\Entity\ShopCategory")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="reference", type="string", length=255, unique=true)
     */
    private $reference;
      
    /**
     * @var string FMElfinderBundle
     * @ORM\Column(name="mainimage", type="string", length=255, nullable=true)
     */
    private $mainimage;
    
    /**
     * @var string FMElfinderBundle
     * @ORM\Column(name="document", type="string", length=255, nullable=true)
     */
    private $document;

    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Back\BlogBundle\Entity\Slider")
     * @ORM\JoinColumn(nullable=true)
     */
    private $slider;
    
    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true, nullable=true)
     */
    private $slug;

    /**
     *
     * @ORM\OneToMany(targetEntity="User_contract", mappedBy="product")
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
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * Get category
     *
     * @return string
     */
    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;
    }

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return Product
     */
    public function setReference($reference) {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference() {
        return $this->reference;
    }
 
    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
     */
    public function setSlug($slug) {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug() {
        return $this->slug;
    }

    /**
     * Set actived
     *
     * @param boolean $actived
     *
     * @return Product
     */
    public function setActived($actived) {
        $this->actived = $actived;

        return $this;
    }

    /**
     * Get actived
     *
     * @return boolean
     */
    public function getActived() {
        return $this->actived;
    }
   
    /**
     * Add usercontract
     *
     * @param User_contract $usercontract
     *
     * @return ShopProduct
     */
    public function addUsercontract(User_contract $usercontract)
    {
        $this->usercontract[] = $usercontract;

        return $this;
    }

    /**
     * Remove usercontract
     *
     * @param User_contract $usercontract
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
     * Set mainimage
     *
     * @param string $mainimage
     *
     * @return ShopProduct
     */
    public function setMainimage($mainimage)
    {
        $this->mainimage = $mainimage;

        return $this;
    }

    /**
     * Get mainimage
     *
     * @return string
     */
    public function getMainimage()
    {
        return $this->mainimage;
    }

    /**
     * Set excerpt
     *
     * @param string $excerpt
     *
     * @return ShopProduct
     */
    public function setExcerpt($excerpt)
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    /**
     * Get excerpt
     *
     * @return string
     */
    public function getExcerpt()
    {
        return $this->excerpt;
    }

    /**
     * Set content
     *
     * @param string $content
     *
     * @return ShopProduct
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set slider
     *
     * @param Slider $slider
     *
     * @return ShopProduct
     */
    public function setSlider(Slider $slider = null)
    {
        $this->slider = $slider;

        return $this;
    }

    /**
     * Get slider
     *
     * @return Slider
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * Set document
     *
     * @param string $document
     *
     * @return ShopProduct
     */
    public function setDocument($document)
    {
        $this->document = $document;

        return $this;
    }

    /**
     * Get document
     *
     * @return string
     */
    public function getDocument()
    {
        return $this->document;
    }
}

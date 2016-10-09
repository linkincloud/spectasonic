<?php

/**
 * Attention ! Cette entité Vendor ajoute des informations supplémentaires à un utilisateur qui possède le ROLE_VENDEUR
 * la relation est un OneToOne avec l'attribut $more dans l'entité SpectasonicFrontUserBundle:User
 */

namespace Spectasonic\Back\UserManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Vendor
 *
 * @ORM\Table(name="vendor")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\UserManagerBundle\Repository\VendorRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Vendor
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
     * @var string FMElfinderBundle
     * @ORM\Column(name="mainimage", type="string", length=255, nullable=true)
     */
    private $mainimage;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="url_to_vendor", type="string", length=255, nullable=true)
     */
    private $urlToVendor;
    
    /**
     * @var string
     *
     * @ORM\Column(name="url_to_product", type="string", length=255, nullable=true)
     */
    private $urlToProduct;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Back\UserManagerBundle\Entity\Country")
     * @ORM\JoinColumn(nullable=false)
     */
    private $country;
    
  
    public function getId()
    {
        return $this->id;
    }

    public function setMainimage($mainimage)
    {
        $this->mainimage = $mainimage;

        return $this;
    }

    public function getMainimage()
    {
        return $this->mainimage;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setUrlToVendor($urlToVendor)
    {
        $this->urlToVendor = $urlToVendor;

        return $this;
    }

    public function getUrlToVendor()
    {
        return $this->urlToVendor;
    }

    public function setUrlToProduct($urlToProduct)
    {
        $this->urlToProduct = $urlToProduct;

        return $this;
    }

    public function getUrlToProduct()
    {
        return $this->urlToProduct;
    }

    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function increase(){
        $this->getCountry()->increaseVendor();
    }
    
    /**
     * @ORM\PreRemove
     */
    public function decrease(){
        $this->getCountry()->decreaseVendor();
    }
}

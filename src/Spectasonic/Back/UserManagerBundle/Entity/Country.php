<?php

namespace Spectasonic\Back\UserManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * Country
 *
 * @ORM\Table(name="country")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\UserManagerBundle\Repository\CountryRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Country
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
     * @ORM\Column(name="name_fr", type="string", length=255, unique=true)
     */
    private $nameFr;

    /**
     * @var string
     *
     * @ORM\Column(name="name_en", type="string", length=255, nullable=true, unique=true)
     */
    private $nameEn;

    /**
     * @Gedmo\Slug(fields={"nameFr"})
     * @ORM\Column(length=255, unique=true, nullable=false)
     */
    private $slug;

    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Back\UserManagerBundle\Entity\Continent")
     * @ORM\JoinColumn(nullable=false)
     */
    private $continent; 

    /**
     * @ORM\Column(name="nb_vendor", type="integer")
     */
    private $nbVendor = 0;
   
    public function getId()
    {
        return $this->id;
    }

    public function setNameFr($nameFr)
    {
        $this->nameFr = $nameFr;

        return $this;
    }
  
    public function getNameFr()
    {
        return $this->nameFr;
    }

    public function setNameEn($nameEn)
    {
        $this->nameEn = $nameEn;

        return $this;
    }

    public function getNameEn()
    {
        return $this->nameEn;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function setContinent(Continent $continent)
    {
        $this->continent = $continent;

        return $this;
    }

    public function getContinent()
    {
        return $this->continent;
    }
    
    /**
     * @ORM\PrePersist
     */
    public function increase(){
        $this->getContinent()->increaseCountry();
    }
    
    /**
     * @ORM\PreRemove
     */
    public function decrease(){
        $this->getContinent()->decreaseCountry();
    }

    public function setNbVendor($nbVendor)
    {
        $this->nbVendor = $nbVendor;

        return $this;
    }

    public function getNbVendor()
    {
        return $this->nbVendor;
    }
    
    public function increaseVendor(){
        $this->nbVendor++;
    }
    
    public function decreaseVendor(){
        $this->nbVendor--;
    }
}

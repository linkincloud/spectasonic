<?php

namespace Spectasonic\Back\UserManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Continent
 *
 * @ORM\Table(name="continent")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\UserManagerBundle\Repository\ContinentRepository")
 * 
 */
class Continent
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
     *
     * @ORM\Column(name="nb_country", type="integer")
     */
    private $nbCountry = 0;
 
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

    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    public function getSlug()
    {
        return $this->slug;
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

    public function setNbCountry($nbCountry)
    {
        $this->nbCountry = $nbCountry;

        return $this;
    }

    public function getNbCountry()
    {
        return $this->nbCountry;
    }
    
    public function increaseCountry(){
        $this->nbCountry++;
    }
    
    public function decreaseCountry(){
        $this->nbCountry--;
    }
}

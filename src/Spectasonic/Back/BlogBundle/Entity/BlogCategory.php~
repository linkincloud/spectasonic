<?php

namespace Spectasonic\Back\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="blog_category")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\BlogBundle\Repository\BlogCategoryRepository")
 */
class BlogCategory
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     * @ORM\Column(length=255, unique=true)
     */
    private $slug;
    
    /**
     * @var string FMElfinderBundle
     * @ORM\Column(name="mainimage", type="string", length=255, nullable=true)
     */
    private $mainimage;
    
    // Getters et setters
    public function getId()
    {
        return $this->id;
    }
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return BlogCategory
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set mainimage
     *
     * @param string $mainimage
     *
     * @return BlogCategory
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
}

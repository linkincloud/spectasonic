<?php

namespace Spectasonic\Back\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slider
 *
 * @ORM\Table(name="configurator_slider_homepage")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\CoreBundle\Repository\SliderRepository")
 */
class Slider
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string FMElfinderBundle
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;
    
    /**
     * @var string FMElfinderBundle
     * @ORM\Column(name="description", type="string", length=255, nullable=true)
     */
    private $description;
    
    /**
     * @var string FMElfinderBundle
     * @ORM\Column(name="mainimage", type="string", length=255, nullable=true)
     */
    private $mainimage;

    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Back\BlogBundle\Entity\Slider")
     * @ORM\JoinColumn(nullable=true)
     */
    private $slider;
    
    /**
     * @var string FMElfinderBundle
     * @ORM\Column(name="video", type="string", length=255, nullable=true)
     */
    private $video;

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
     * Set mainimage
     * @param string $mainimage
     * @return Slider
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
     * Set slider
     * @param string $slider
     * @return Slider
     */
    public function setSlider($slider)
    {
        $this->slider = $slider;

        return $this;
    }

    /**
     * Get slider
     * @return string
     */
    public function getSlider()
    {
        return $this->slider;
    }

    /**
     * Set video
     * @param string $video
     * @return Slider
     */
    public function setVideo($video)
    {
        $this->video = $video;

        return $this;
    }

    /**
     * Get video
     * @return string
     */
    public function getVideo()
    {
        return $this->video;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Slider
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Slider
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}

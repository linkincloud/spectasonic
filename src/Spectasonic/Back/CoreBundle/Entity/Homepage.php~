<?php

namespace Spectasonic\Back\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Spectasonic\Back\CoreBundle\Entity\Slider;
/**
 * Homepage
 *
 * @ORM\Table(name="configurator_homepage")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\CoreBundle\Repository\HomepageRepository")
 */
class Homepage
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
     * @var bool
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=true)
     */
    private $enabled;

    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Back\CoreBundle\Entity\Slider")
     * @ORM\JoinColumn(nullable=true)
     */
    private $slider;
    
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
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Homepage
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set slider
     *
     * @param $slider
     *
     * @return Homepage
     */
    public function setSlider(Slider $slider = null)
    {
        $this->slider = $slider;

        return $this;
    }

    /**
     * Get slider
     *
     * @return \Spectasonic\Back\CoreBundle\Entity\Slider
     */
    public function getSlider()
    {
        return $this->slider;
    }
}

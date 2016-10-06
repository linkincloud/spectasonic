<?php

namespace Spectasonic\Back\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="blog_comment")
 * @ORM\Entity(repositoryClass="Spectasonic\Back\BlogBundle\Repository\BlogCommentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class BlogComment
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="author", type="string", length=255)
     */
    private $author;

    /**
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Spectasonic\Back\BlogBundle\Entity\Blog", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blog;

    public function __construct()
    {
        $this->date = new \Datetime();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setBlog(Blog $blog)
    {
        $this->blog = $blog;

        return $this;
    }

    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * @ORM\PrePersist
     */
    public function increase()
    {
        $this->getBlog()->increaseComment();
    }

    /**
     * @ORM\PreRemove
     */
    public function decrease()
    {
        $this->getBlog()->decreaseComment();
    }
}

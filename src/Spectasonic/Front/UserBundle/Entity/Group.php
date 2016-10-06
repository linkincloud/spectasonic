<?php

namespace Spectasonic\Front\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\Group as BaseGroup;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_group") 
 */
class Group extends BaseGroup {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var
     *
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    protected $users;

    /**
     * Constructor
     */
    public function __construct($name, $roles) {
        parent::__construct($name, $roles);
        $this->users = new ArrayCollection();
    }

    /**
     * @param $user
     * @return $this
     */
    public function addUser($user) {
        $this->users[] = $user;
        $user->setGroup($this);

        return $this;
    }

    /**
     * @param $users
     */
    public function setUsers($users) {
        $this->users->clear();
        foreach ($users as $user) {
            $this->addUser($user);
        }
    }

    /**
     * @return mixed
     */
    public function getUsers() {
        return $this->users;
    }

}

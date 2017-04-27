<?php


namespace UserBundle\Entity;

use AppBundle\Entity\Comment;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @ORM\OneToOne(targetEntity="UserBundle\Entity\Avatar", cascade={"remove","persist"})
     */
    private $avatar;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="author", cascade={"remove","persist"})
     */
    private $comments;

    public function __construct()
    {
        parent::__construct();
        $this->comments = new ArrayCollection();

    }

    /**
     * Add comment
     *
     * @param Comment $comment
     *
     * @return User
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param Comment $comment
     */
    public function removeComment(Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set avatar
     *
     * @param \UserBundle\Entity\Avatar $avatar
     *
     * @return User
     */
    public function setAvatar(\UserBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;
        return $this;
    }

    /**
     * Get avatar
     *
     * @return \UserBundle\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }
}

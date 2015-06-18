<?php

namespace DW\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use DW\ActivityBundle\Entity\Activity;
use DW\DocumentaryBundle\Entity\Documentary;
use DW\LikeBundle\Entity\Like;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMS;
use FOS\UserBundle\Model\User as BaseUser;
use DW\CommentBundle\Entity\Comment;

/**
 * @ORM\Entity(repositoryClass="DW\UserBundle\Repository\Doctrine\ORM\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="user", indexes={
 *      @ORM\Index(name="username_idx", columns={"username", "username"}),
 *      @ORM\Index(name="email_idx", columns={"email", "email"})
 * })
 *
 * @JMS\ExclusionPolicy("all")
 */
class User extends BaseUser implements UserInterface, \Serializable, EquatableInterface
{
    /**
     * @var int $id
     *
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DW\CommentBundle\Entity\Comment", mappedBy="user", cascade="persist")
     */
    protected $comments;

    /**
     * @var Activity[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DW\ActivityBundle\Entity\Activity", mappedBy="user", cascade={"persist", "merge"})
     */
    protected $activity;

    /**
     * @var Like[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="DW\LikeBundle\Entity\Like", mappedBy="user", cascade="persist")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    protected $likes;

    /**
     * @var ArrayCollection|Documentary[]
     */
    protected $likedDocumentaries;

    /**
     * @ORM\OneToOne(targetEntity="Facebook")
     *
     * @var Facebook $facebook
     */
    private $facebook;

    /**
     * @var ArrayCollection|Documentary[]
     *
     * @ORM\OneToMany(targetEntity="DW\DocumentaryBundle\Entity\Documentary", mappedBy="addedBy")
     * @ORM\OrderBy({"created" = "DESC"})
     */
    protected $addedDocumentaries;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $displayName;

    /**
     * @return ArrayCollection|Avatar[]
     *
     * @ORM\OneToMany(targetEntity="DW\UserBundle\Entity\Avatar", mappedBy="user", cascade="persist")
     */
    private $avatars;

    /**
     * @ORM\OneToOne(targetEntity="DW\UserBundle\Entity\Avatar")
     */
    private $mainAvatar;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->likes = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->activity = new ArrayCollection();
        $this->likedDocumentaries = new ArrayCollection();
        $this->addedDocumentaries = new ArrayCollection();
        $this->avatars = new ArrayCollection();
    }

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
     * Has comment
     *
     * @param Comment $comment
     * @return boolean
     */
    public function hasComment(Comment $comment)
    {
        return $this->comments->contains($comment);
    }

    /**
     * Add comments
     *
     * @param Comment $comment
     * @return User
     */
    public function addComment(Comment $comment)
    {
        if (!$this->hasComment($comment)) {
            $this->comments->add($comment);
        }

        return $this;
    }

    /**
     * Remove comments
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
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @see \Serializable::serialize()
     */
    public function serialize()
    {
        return serialize(array(
            $this->id,
        ));
    }

    /**
     * @see \Serializable::unserialize()
     */
    public function unserialize($serialized)
    {
        list (
            $this->id,
            ) = unserialize($serialized);
    }

    private function hasActivity(Activity $activity)
    {
        return $this->activity->contains($activity);
    }

    /**
     * Add activity
     *
     * @param Activity $activity
     * @return User
     */
    public function addActivity(Activity $activity)
    {
        if (!$this->hasActivity($activity)) {
            $this->activity->add($activity);
        }
    }

    /**
     * Get activity
     *
     * @return ArrayCollection
     */
    public function getActivity()
    {
        return $this->activity;
    }

    /**
     * Get likes
     *
     * @return ArrayCollection
     */
    public function getLikes()
    {
        return $this->likes;
    }

    /**
     * Has like
     *
     * @param Like $like
     * @return boolean
     */
    public function hasLike(Like $like)
    {
        return $this->likes->contains($like);
    }

    /**
     * Add like
     *
     * @param Like $like
     */
    public function addLike(Like $like)
    {
        if (!$this->hasLike($like)) {
            $this->likes->add($like);
        }
    }

    /**
     * Remove like
     */
    public function removeLike(Like $like)
    {
        if ($this->hasLike($like)) {
            $this->likes->remove($like);
        }
    }

    /**
     * Get likedDocumentaries
     *
     * @return Documentary[]|ArrayCollection
     */
    public function getLikedDocumentaries()
    {
        return $this->likedDocumentaries;
    }

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     */
    function getGravatar( $email, $s = 80, $d = 'identicon', $r = 'g', $img = false, $atts = array() ) {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5( strtolower( trim( $email ) ) );
        $url .= "?s=$s&d=$d&r=$r";
        if ( $img ) {
            $url = '<img src="' . $url . '"';
            foreach ( $atts as $key => $val )
                $url .= ' ' . $key . '="' . $val . '"';
            $url .= ' />';
        }
        return $url;
    }

    /**
     * Has added
     *
     * @param Documentary $documentary
     * @return bool
     */
    public function hasAddedDocumentary(Documentary $documentary)
    {
        return $this->addedDocumentaries->contains($documentary);
    }

    /**
     * Add addedDocumentary
     *
     * @param Documentary $documentary
     */
    public function addAddedDocumentary(Documentary $documentary)
    {
        if (!$this->hasAddedDocumentary($documentary)) {
            $this->addedDocumentaries->add($documentary);
            $documentary->setAddedBy($this);
        }
    }

    /**
     * Remove addedDocumentary
     *
     * @param Documentary $documentary
     */
    public function removeAddedDocumentary(Documentary $documentary)
    {
        if ($this->hasAddedDocumentary($documentary)) {
            $this->addedDocumentaries->removeElement($documentary);
        }
    }

    public function isEqualTo(UserInterface $user)
    {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     * @return Facebook
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * @param Facebook $facebook
     */
    public function setFacebook(Facebook $facebook)
    {
        $this->facebook = $facebook;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return ArrayCollection|Avatar[]
     */
    public function getAvatars()
    {
        return $this->avatars;
    }

    /**
     * @param array|Avatar[]
     */
    public function setAvatars(array $avatars)
    {
        foreach ($avatars as $avatar) {
            $this->addAvatar($avatar);
        }
    }

    /**
     * @param Avatar $avatar
     * @return bool
     */
    public function hasAvatar(Avatar $avatar)
    {
        return $this->avatars->contains($avatar);
    }

    /**
     * @param Avatar $avatar
     */
    public function addAvatar(Avatar $avatar)
    {
        if (!$this->hasAvatar($avatar)) {
            $this->addAvatar($avatar);
        }
    }

    /**
     * @param Avatar $avatar
     */
    public function removeAvatar(Avatar $avatar)
    {
        $this->avatars->removeElement($avatar);
    }

    /**
     * @return Avatar
     */
    public function getMainAvatar()
    {
        return $this->mainAvatar;
    }

    /**
     * @param Avatar $mainAvatar
     */
    public function setMainAvatar(Avatar $mainAvatar)
    {
        $this->mainAvatar = $mainAvatar;
    }

    /**
     * Remove main avatar
     */
    public function removeMainAvatar()
    {
        $this->mainAvatar = null;
    }
}
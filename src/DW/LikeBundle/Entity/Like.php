<?php

namespace DW\LikeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DW\DocumentaryBundle\Entity\Documentary;
use DW\UserBundle\Entity\User;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;

/**
 * @ORM\Entity(repositoryClass="DW\LikeBundle\Repository\Doctrine\ORM\LikeRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="likes", uniqueConstraints={
 *     @ORM\UniqueConstraint(name="like_idx", columns={"user_id", "documentary_id"})})
 *
 * @JMS\ExclusionPolicy("all")
 */
class Like
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
    private $id;

    /**
     * @var User $user
     *
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\User")
     *
     * @ORM\ManyToOne(targetEntity="DW\UserBundle\Entity\User", inversedBy="likes", cascade="persist")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var Documentary $documentary
     *
     * @JMS\Expose
     * @JMS\Type("DW\DWBundle\Entity\Documentary")
     *
     * @ORM\ManyToOne(targetEntity="DW\DocumentaryBundle\Entity\Documentary", inversedBy="likes", cascade="persist")
     * @ORM\JoinColumn(name="documentary_id", referencedColumnName="id")
     */
    private $documentary;

    /**
     * @var \DateTime $created
     *
     * @JMS\Expose
     * @JMS\Type("DateTime")
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created;

    public function __construct()
    {
        $this->created = new \DateTime();
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
     * Set user
     *
     * @param User $user
     * @return Like
     */
    public function setUser(User $user)
    {
        $this->user = $user;
        $user->addLike($this); //todo
    }

    /**
     * Get user
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->user->getId();
    }

    /**
     * Set documentary
     *
     * @param Documentary $documentary
     * @return Like
     */
    public function setDocumentary(Documentary $documentary)
    {
        $this->documentary = $documentary;
        $documentary->addLike($this);
    }

    /**
     * Get documentary
     *
     * @return Documentary
     */
    public function getDocumentary()
    {
        return $this->documentary;
    }

    /**
     * Get documentaryId
     *
     * @return integer
     */
    public function getDocumentaryId()
    {
        return $this->documentary->getId();
    }

    /**
     * Get documentarySlug
     *
     * @return string
     */
    public function getDocumentarySlug()
    {
        return $this->documentary->getSlug();
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Documentary
     */
    public function setCreated($created)
    {
        $this->created = $created;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @ORM\PrePersist
     */
    public function onPrePersist()
    {
        $this->documentary->incrementLikeCount();
    }

    /**
     * @ORM\PreRemove
     */
    public function onPreRemove()
    {
        $this->documentary->decrementLikeCount();
    }
}

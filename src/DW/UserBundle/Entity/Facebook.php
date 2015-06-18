<?php

namespace DW\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DW\UserBundle\Repository\Doctrine\ORM\UserRepository")
 * @ORM\Table(name="facebook")
 */
class Facebook
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $accessToken
     *
     * @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true)
     */
    private $accessToken;

    /**
     * @var string $avatarFull
     *
     * @ORM\Column(name="facebook_avatar_full", type="string", nullable=true)
     */
    private $avatarFull;

    /**
     * @var string $avatarThumb
     *
     * @ORM\Column(name="facebook_avatar_thumb", type="string", nullable=true)
     */
    private $avatarThumb;

    /**
     * @var string $avatar2
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar2;

    /**
     * @var User $user
     *
     * @ORM\OneToOne(targetEntity="DW\UserBundle\Entity\User")
     */
    private $user;

    public function __construct()
    {
        /* blank */
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;
    }

    public function getAvatarFull()
    {
        return $this->avatarFull;
    }

    public function setAvatarFull($avatarFull)
    {
        $this->avatarFull = $avatarFull;
    }

    /**
     * @return mixed
     */
    public function getAvatarThumb()
    {
        return $this->avatarThumb;
    }

    /**
     * @param $avatarThumb
     */
    public function setAvatarThumb($avatarThumb)
    {
        $this->avatarFull = $avatarThumb;
    }

    /**
     * @return mixed
     */
    public function getAvatar2()
    {
        return $this->avatar2;
    }

    /**
     * @param $avatar2
     */
    public function setAvatar2($avatar2)
    {
        $this->avatar2 = $avatar2;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
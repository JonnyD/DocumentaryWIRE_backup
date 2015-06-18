<?php

namespace DW\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="DW\UserBundle\Repository\Doctrine\ORM\UserRepository")
 * @ORM\HasLifecycleCallbacks
 * @ORM\Table(name="avatar")
 */
class Avatar
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
     * @var string $avatar
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $avatar;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    private $temp;

    /**
     * @var User $user
     *
     * @ORM\ManyToOne(targetEntity="DW\UserBundle\Entity\User", inversedBy="avatars", cascade="persist", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\Column(type="boolean", length=1, nullable=true)
     */
    private $main = false;

    public function __construct()
    {
        /* blank */
    }

    /**
     * Get avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get avatar directory
     */
    public function getAvatarDirectory()
    {
        return 'uploads/images/avatar/';
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        // check if we have an old image path
        if (is_file($this->getAbsolutePath())) {
            // store the old name to delete after the update
            $this->temp = $this->getAbsolutePath();
        } else {
            $this->avatar = 'initial';
        }
    }

    /**
     * Get file
     *
     * @return UploadedFile $file
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        if ($this->avatar !== null) {
            return $this->getAvatarRootDir().'/'.$this->avatar;
        }
    }

    protected function getAvatarRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getAvatarDirectory();
    }

    public function preUpload()
    {
        if ($this->getFile() !== null) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->avatar = $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    public function upload()
    {
        if ($this->getFile() === null) {
            return;
        }

        // check if we have an old image
        if (isset($this->temp)) {
            // delete the old image
            unlink($this->temp);
            // clear the temp image path
            $this->temp = null;
        }

        // you must throw an exception here if the file cannot be moved
        // so that the entity is not persisted to the database
        // which the UploadedFile move() method doe

        $this->getFile()->move($this->getAvatarRootDir(), $this->avatar);
        $resized = $this->resizeImage($this->getAbsolutePath(), 200, 200);
        $this->setFile(null);
    }

    public function resizeImage($file, $w, $h)
    {
        list($width, $height) = getimagesize($file);
        $r = $width / $height;
        if ($w/$h > $r) {
            $newwidth = $h*$r;
            $newheight = $h;
        } else {
            $newheight = $w/$r;
            $newwidth = $w;
        }
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($src, $dst, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($src, $dst, 90);
        return $dst;
    }

    /**
     * @ORM\PreRemove()
     */
    public function storeFilenameForRemove()
    {
        $this->temp = $this->getAbsolutePath();
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if (isset($this->temp)) {
            unlink($this->temp);
        }
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

    /**
     * @return bool
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * @param $main
     */
    public function setMain($main)
    {
        $this->main = $main;
    }

    /**
     * Set as main avatar
     */
    public function setAsMainAvatar()
    {
        $this->main = true;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateMainAvatar()
    {
        if ($this->main) {
            $user = $this->user;
            $oldMainAvatar = $user->getMainAvatar();

            if ($oldMainAvatar == null
                || $oldMainAvatar != $this) {
                $user->setMainAvatar($this);
            }
        }
    }

    /**
     * @ORM\PreRemove
     */
    public function removeMainAvatar()
    {
        if ($this->main) {
            $this->user->removeMainAvatar();
        }
    }
}
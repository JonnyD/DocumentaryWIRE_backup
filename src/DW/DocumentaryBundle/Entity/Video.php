<?php

namespace DW\DocumentaryBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="DW\DocumentaryBundle\Repository\Doctrine\ORM\VideoRepository")
 * @ORM\Table(name="video")
 */
class Video
{
    /**
     * @var int $id
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Documentary $documentary
     *
     * @ORM\ManyToOne(targetEntity="DW\DocumentaryBundle\Entity\Documentary", inversedBy="videos", cascade="persist")
     * @ORM\JoinColumn(name="documentary_id", referencedColumnName="id")
     */
    private $documentary;

    /**
     * @var string $url
     *
     * @ORM\Column(type="string")
     */
    private $url;

    /**
     * @var string $source
     *
     * @ORM\Column(type="string")
     */
    private $source;

    /**
     * @var string $videoId
     *
     * @ORM\Column(type="string")
     */
    private $videoId;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param Documentary $documentary
     */
    public function setDocumentary(Documentary $documentary)
    {
        $this->documentary = $documentary;
    }

    /**
     * @return Documentary
     */
    public function getDocumentary()
    {
        return $this->documentary;
    }

    /**
     * @param string $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param string $source
     */
    public function setSource($source)
    {
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @param string $videoId
     */
    public function setVideoId($videoId)
    {
        $this->videoId = $videoId;
    }

    /**
     * @return string
     */
    public function getVideoId()
    {
        return $this->videoId;
    }
}
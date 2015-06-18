<?php

namespace DW\CategoryBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JMS;
use DW\DocumentaryBundle\Entity\Documentary;

/**
 * @ORM\Entity(repositoryClass="DW\CategoryBundle\Repository\Doctrine\ORM\CategoryRepository")
 * @ORM\Table(name="category")
 *
 * @JMS\ExclusionPolicy("all")
 */
class Category
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
     * @var string $name
     *
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
     * @var string $slug
     *
     * @JMS\Expose
     * @JMS\Type("string")
     *
	 * @Gedmo\Slug(fields={"name"}, updatable=false)
	 * @ORM\Column(type="string")
	 */
	protected $slug;

	/**
     * @var ArrayCollection|Documentary[] $documentaries
     *
	 * @ORM\OneToMany(targetEntity="DW\DocumentaryBundle\Entity\Documentary", mappedBy="category", cascade="persist")
     * @ORM\OrderBy({"created" = "DESC"})
	 */
	protected $documentaries;

    /**
     * @var int $count
     *
     * @JMS\Expose
     * @JMS\Type("integer")
     *
     * @ORM\Column(type="integer")
     */
    protected $count = 0;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documentaries = new ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Category
     */
    public function setName($name)
    {
        $this->name = $name;
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
     * Set slug
     *
     * @param string $slug
     * @return Category
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Has documentary
     *
     * @param Documentary $documentary
     * @return boolean
     */
    public function hasDocumentary(Documentary $documentary)
    {
        return $this->documentaries->contains($documentary);
    }

    /**
     * Add documentaries
     *
     * @param Documentary $documentary
     * @return Category
     */
    public function addDocumentary(Documentary $documentary)
    {
        if (!$this->documentaries->contains($documentary)) {
            $this->documentaries[] = $documentary;
            $this->count++;
        }
    }

    /**
     * Get documentaries
     *
     * @return ArrayCollection|Documentary[]
     */
    public function getDocumentaries()
    {
        return $this->documentaries;
    }

    /**
     * Set count
     *
     * @param integer $count
     * @return Category
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Get count
     *
     * @return integer 
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * Increment count
     */
    public function incrementCount()
    {
        $this->count++;
    }

    /**
     * Decrement count
     */
    public function decrementCount()
    {
        $this->count--;
    }

    public function __toString()
    {
        return $this->name;
    }
}
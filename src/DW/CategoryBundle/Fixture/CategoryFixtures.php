<?php

namespace DW\CategoryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use DW\CategoryBundle\Entity\Category;
use DW\CategoryBundle\Service\CategoryService;

class CategoryFixtures extends AbstractFixture implements ContainerAwareInterface
{
    private $container;
    private $manager;

    public function setContainer(ContainerInterface $container = null)
    {
    	$this->container = $container;
    }
    
	public function load(ObjectManager $manager)
	{
        $this->manager = $manager;

        $category1 = $this->createCategory("911", "911");
        $category2 = $this->createCategory("Art & Artists", "art-artists");
        $category3 = $this->createCategory("Biography", "biography");
        $category4 = $this->createCategory("Comedy", "comedy");
        $category5 = $this->createCategory("Conspiracy", "conspiracy");
        $category6 = $this->createCategory("Drugs", "drugs");
        $category7 = $this->createCategory("Economics", "economics");
        $category8 = $this->createCategory("Environment", "environment");
        $category9 = $this->createCategory("Health", "health");
        $category10 = $this->createCategory("History", "history");
        $category11 = $this->createCategory("Law", "law");
        $category12 = $this->createCategory("Media", "media");
        $category13 = $this->createCategory("Miltary & Law", "military-law");
        $category14 = $this->createCategory("Mystery", "mystery");
        $category15 = $this->createCategory("Nature", "nature");
        $category16 = $this->createCategory("Performing Arts", "performing-arts");
        $category17 = $this->createCategory("Philosophy", "philosophy");
        $category18 = $this->createCategory("Politics", "politics");
        $category19 = $this->createCategory("Preview Only", "preview-only");
        $category20 = $this->createCategory("Psychology", "psychology");
        $category21 = $this->createCategory("Religion", "religion");
        $category22 = $this->createCategory("Science", "science");
        $category23 = $this->createCategory("Sexuality", "sexuality");
        $category24 = $this->createCategory("Society", "society");
        $category25 = $this->createCategory("Sports", "sports");
        $category26 = $this->createCategory("Technology", "technology");
        $category27 = $this->createCategory("Uncategorized", "uncategorized");

		$manager->flush();
	}

    private function createCategory($name, $slug)
    {
        $category = new Category();
        $category->setName($name);
        $category->setSlug($slug);

        $this->manager->persist($category);
        $this->addReference($slug, $category);
        return $category;
    }

    /**
     * @return CategoryService
     */
    private function getCategoryService()
    {
        return $this->container->get('documentary_wire.category_manager');
    }
}
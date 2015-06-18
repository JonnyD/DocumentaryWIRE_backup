<?php

namespace DW\CategoryBundle\Repository\Doctrine\ORM;

use Doctrine\ORM\EntityRepository;
use DW\CategoryBundle\Repository\CategoryRepository as CategoryRepositoryInterface;

/**
 * CategoryRepository
 */
class CategoryRepository extends EntityRepository implements CategoryRepositoryInterface
{
    public function findAllCategories()
    {
        return $query = $this->getEntityManager()
            ->createQuery('SELECT c FROM DocumentaryWIREBundle:Category c
                            ORDER BY c.name ASC')
            ->getResult();
    }

    public function findCategoriesWithDocumentaries()
    {
        return $query = $this->getEntityManager()
            ->createQuery('SELECT c FROM DocumentaryWIREBundle:Category c
                            WHERE c.count > 0
                            ORDER BY c.name ASC')
            ->getResult();
    }

    public function findOneByName($name)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c FROM DocumentaryWIREBundle:Category c
                WHERE c.name = :name
            ')
            ->setParameter("name", $name);

        return $query->getSingleResult();
    }

    public function findOneBySlug($slug)
    {
        $query = $this->getEntityManager()
            ->createQuery('
                SELECT c FROM DocumentaryWIREBundle:Category c
                WHERE c.slug = :slug
            ')
            ->setParameter("slug", $slug);

        return $query->getSingleResult();
    }

    public function merge($entity)
    {
        $this->getEntityManager()->merge($entity);
    }

    public function save($entity, $sync = false)
    {
        $this->getEntityManager()->persist($entity);
        if ($sync) {
            $this->flush();
        }
    }

    public function flush()
    {
        $this->getEntityManager()->flush();
    }
}
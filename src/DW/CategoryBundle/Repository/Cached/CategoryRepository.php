<?php

namespace DW\DWBundle\Repository\Cached;

use Doctrine\ORM\EntityRepository;
use DW\BaseBundle\Cache\CategoryCache;
use DW\BaseBundle\Helper\CacheHelper;
use DW\BaseBundle\Repository\Cached\CustomRepository;
use DW\CategoryBundle\Repository\CategoryRepository as CategoryRepositoryInterface;
use DW\CategoryBundle\Repository\Doctrine\ORM\CategoryRepository as CategoryRepositoryDoctrine;

class CategoryRepository extends EntityRepository implements CategoryRepositoryInterface
{
    private $categoryRepository;
    private $cacheHelper;

    public function __construct(CategoryRepositoryDoctrine $categoryRepository, CacheHelper $cacheHelper)
    {
        $this->categoryRepository = $categoryRepository;
        $this->cacheHelper = $cacheHelper;
    }

    public function findAllCategories()
    {
        $key = CategoryCache::KEY_LIST;
        $name = CategoryCache::CACHE_CATEGORY;
        $categories = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Category>");
        if ($categories == null) {
            $categories = $this->categoryRepository->findAllCategories();
            $this->cacheHelper->saveToCache($key, $name, $categories);
        }
        return $categories;
    }

    public function findCategoriesWithDocumentaries()
    {
        $key = CategoryCache::KEY_LIST_WITH_DOCUMENTARIES;
        $name = CategoryCache::CACHE_CATEGORY;
        $categories = $this->cacheHelper->getFromCache($key, $name, "ArrayCollection<DW\DWBundle\Entity\Category>");
        if ($categories == null) {
            $categories = $this->categoryRepository->findCategoriesWithDocumentaries();
            $this->cacheHelper->saveToCache($key, $name, $categories);
        }
        return $categories;
    }

    public function findOneByName($name)
    {
        $key = CategoryCache::KEY_NAME."_".$name;
        $cacheName = CategoryCache::CACHE_CATEGORY;
        $category = $this->cacheHelper->getFromCache($key, $cacheName, "DW\DWBundle\Entity\Category");
        if ($category == null) {
            $category = $this->categoryRepository->findOneByName($name);
            $this->cacheHelper->saveToCache($key, $cacheName, $category);
        }
        return $category;
    }

    public function findOneBySlug($slug)
    {
        $key = CategoryCache::KEY_SLUG."_".$slug;
        $cacheName = CategoryCache::CACHE_CATEGORY;
        $category = $this->cacheHelper->getFromCache($key, $cacheName, "DW\DWBundle\Entity\Category");
        if ($category == null) {
            $category = $this->categoryRepository->findOneBySlug($slug);
            $this->cacheHelper->saveToCache($key, $cacheName, $category);
        }
        return $category;
    }

    public function merge($entity)
    {
       $this->categoryRepository->merge($entity);
    }

    public function save($entity, $sync = false)
    {
        $this->categoryRepository->save($entity, $sync);
    }

    public function flush()
    {
        $this->categoryRepository->flush();
    }
}
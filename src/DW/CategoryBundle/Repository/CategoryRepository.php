<?php

namespace DW\CategoryBundle\Repository;

interface CategoryRepository
{
    public function findAllCategories();
    public function findCategoriesWithDocumentaries();
    public function findOneByName($name);
    public function findOneBySlug($slug);
    public function merge($entity);
    public function save($entity, $sync = false);
    public function flush();
}
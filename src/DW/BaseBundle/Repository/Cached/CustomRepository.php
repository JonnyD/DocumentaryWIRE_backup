<?php

namespace DW\BaseBundle\Repository\Cached;

use DW\BaseBundle\Repository\Repository;

class CustomRepository implements Repository
{
    private $repository;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    public function merge($entity)
    {
        return $this->repository->merge($entity);
    }

    public function save($entity, $sync = true)
    {
        $this->repository->save($entity, $sync);
    }

    public function flush()
    {
        $this->repository->flush();
    }
}
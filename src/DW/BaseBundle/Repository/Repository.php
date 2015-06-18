<?php

namespace DW\BaseBundle\Repository;

interface Repository
{
    public function merge($entity);
    public function save($entity, $sync = true);
    public function flush();
}
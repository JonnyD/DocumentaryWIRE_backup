<?php

namespace DW\UserBundle\Service;

use Doctrine\ORM\EntityManager;
use DW\UserBundle\Repository\Doctrine\ORM\RoleRepository;

class RoleService
{
    private $roleRepository;

    public function __construct(RoleRepository $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function getRoleByName($name)
    {
        return $this->roleRepository->findOneByName($name);
    }
}
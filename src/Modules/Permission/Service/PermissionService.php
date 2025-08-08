<?php

namespace Modules\Permission\Service;

use Modules\Permission\Repository\PermissionRepository;

class PermissionService
{
    public function __construct(private PermissionRepository $permissions)
    {
    }

    public function list(): array
    {
        return $this->permissions->all();
    }

    public function create(string $name): int
    {
        return $this->permissions->create($name);
    }
}

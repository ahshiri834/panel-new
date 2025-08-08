<?php

namespace Modules\Role\Service;

use Modules\Role\Repository\RoleRepository;

class RoleService
{
    public function __construct(private RoleRepository $roles)
    {
    }

    public function list(): array
    {
        return $this->roles->all();
    }

    public function create(string $name): int
    {
        return $this->roles->create($name);
    }

    public function assignPermission(int $roleId, int $permissionId): bool
    {
        return $this->roles->assignPermission($roleId, $permissionId);
    }

    public function permissions(int $roleId): array
    {
        return $this->roles->permissions($roleId);
    }
}

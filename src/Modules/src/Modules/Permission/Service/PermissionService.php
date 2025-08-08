<?php

namespace Modules\Permission\Service;

use Modules\Permission\Repository\PermissionRepository;

class PermissionService
{
    public function __construct(private PermissionRepository $permRepo)
    {
    }

    public function allow(array $user, string $perm): bool
    {
        $perms = $this->permRepo->permissionsForRole($user['role_id']);
        return in_array($perm, $perms, true);
    }
}

<?php

namespace Modules\Permission\Repository;

use PDO;

class PermissionRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT id,name FROM permissions');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function permissionsForRole(int $roleId): array
    {
        $stmt = $this->db->prepare('SELECT p.name FROM permissions p JOIN role_permission rp ON rp.permission_id = p.id WHERE rp.role_id = :r');
        $stmt->execute(['r' => $roleId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}

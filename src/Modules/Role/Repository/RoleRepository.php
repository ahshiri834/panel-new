<?php

namespace Modules\Role\Repository;

use PDO;

class RoleRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function all(): array
    {
        $stmt = $this->db->query('SELECT id,name FROM roles');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id,name FROM roles WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $role = $stmt->fetch(PDO::FETCH_ASSOC);
        return $role ?: null;
    }

    public function create(string $name): int
    {
        $stmt = $this->db->prepare('INSERT INTO roles (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
        return (int)$this->db->lastInsertId();
    }

    public function assignPermission(int $roleId, int $permissionId): bool
    {
        $stmt = $this->db->prepare('INSERT IGNORE INTO role_permission (role_id, permission_id) VALUES (:role_id, :permission_id)');
        return $stmt->execute(['role_id' => $roleId, 'permission_id' => $permissionId]);
    }

    public function permissions(int $roleId): array
    {
        $stmt = $this->db->prepare('SELECT p.id,p.name FROM permissions p JOIN role_permission rp ON p.id = rp.permission_id WHERE rp.role_id = :role_id');
        $stmt->execute(['role_id' => $roleId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

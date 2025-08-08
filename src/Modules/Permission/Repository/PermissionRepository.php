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

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id,name FROM permissions WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $perm = $stmt->fetch(PDO::FETCH_ASSOC);
        return $perm ?: null;
    }

    public function create(string $name): int
    {
        $stmt = $this->db->prepare('INSERT INTO permissions (name) VALUES (:name)');
        $stmt->execute(['name' => $name]);
        return (int)$this->db->lastInsertId();
    }
}

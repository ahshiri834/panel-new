<?php

namespace Modules\User\Repository;

use PDO;

class UserRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT id,name,email,password_hash,role_id FROM users WHERE email = :email');
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id,name,email,password_hash,role_id FROM users WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare('INSERT INTO users (name,email,password_hash,role_id) VALUES (:name,:email,:password_hash,:role_id)');
        $stmt->execute([
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'password_hash' => $data['password_hash'] ?? '',
            'role_id' => $data['role_id'] ?? 0,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $fields): bool
    {
        $sets = [];
        $params = ['id' => $id];
        foreach (['name', 'email', 'password_hash', 'role_id'] as $f) {
            if (array_key_exists($f, $fields)) {
                $sets[] = "$f = :$f";
                $params[$f] = $fields[$f];
            }
        }
        if (!$sets) {
            return false;
        }
        $sql = 'UPDATE users SET ' . implode(',', $sets) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function assignRole(int $id, int $roleId): bool
    {
        $stmt = $this->db->prepare('UPDATE users SET role_id = :role_id WHERE id = :id');
        return $stmt->execute(['role_id' => $roleId, 'id' => $id]);
    }
}

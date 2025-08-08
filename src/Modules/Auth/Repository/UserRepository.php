<?php

namespace Modules\Auth\Repository;

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
}

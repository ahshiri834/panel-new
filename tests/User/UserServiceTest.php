<?php

use Modules\User\Service\UserService;
use Modules\User\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class UserServiceTest extends TestCase
{
    public function testRegisterAssignRole(): void
    {
        $repo = new class extends UserRepository {
            public array $data = [];
            public function __construct() {}
            public function create(array $data): int { $data['id'] = 1; $this->data[1] = $data; return 1; }
            public function find(int $id): ?array { return $this->data[$id] ?? null; }
            public function update(int $id, array $fields): bool { $this->data[$id] = array_merge($this->data[$id], $fields); return true; }
            public function assignRole(int $id, int $roleId): bool { $this->data[$id]['role_id'] = $roleId; return true; }
            public function findByEmail(string $email): ?array { return null; }
        };
        $service = new UserService($repo);
        $id = $service->register(['name' => 'Ann', 'email' => 'ann@example.com', 'password_hash' => 'hash', 'role_id' => 1]);
        $this->assertSame(1, $id);
        $user = $service->get(1);
        $this->assertSame('Ann', $user['name']);
        $service->assignRole(1, 2);
        $this->assertSame(2, $repo->data[1]['role_id']);
    }
}

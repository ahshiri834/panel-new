<?php

use Modules\Auth\Service\AuthService;
use Modules\Auth\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

class AuthServiceTest extends TestCase
{
    public function testSuccessfulLogin(): void
    {
        $repo = new class extends UserRepository {
            public function __construct()
            {
            }
            public function findByEmail(string $email): ?array
            {
                return [
                    'id' => 1,
                    'name' => 'John',
                    'email' => 'john@example.com',
                    'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
                    'role_id' => 5,
                ];
            }
            public function find(int $id): ?array
            {
                return null;
            }
        };
        $service = new AuthService($repo, 'test-secret');
        $result = $service->login('john@example.com', 'secret');
        $this->assertTrue($result['success']);
        $this->assertNotEmpty($result['token']);
        $this->assertSame(1, $result['user']['id']);
    }

    public function testWrongPassword(): void
    {
        $repo = new class extends UserRepository {
            public function __construct()
            {
            }
            public function findByEmail(string $email): ?array
            {
                return [
                    'id' => 1,
                    'name' => 'John',
                    'email' => 'john@example.com',
                    'password_hash' => password_hash('secret', PASSWORD_DEFAULT),
                    'role_id' => 5,
                ];
            }
            public function find(int $id): ?array
            {
                return null;
            }
        };
        $service = new AuthService($repo, 'test-secret');
        $result = $service->login('john@example.com', 'wrong');
        $this->assertFalse($result['success']);
    }
}

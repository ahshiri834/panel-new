<?php

namespace Modules\Auth\Service;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Modules\Auth\Repository\UserRepository;

class AuthService
{
    public function __construct(
        private UserRepository $users,
        private string $jwtSecret
    ) {
    }

    public function login(string $email, string $password): array
    {
        $user = $this->users->findByEmail($email);
        if (!$user || !$this->verify($password, $user['password_hash'] ?? '')) {
            return ['success' => false, 'message' => 'Wrong credentials'];
        }

        $now = time();
        $payload = [
            'sub' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role_id'],
            'iat' => $now,
            'exp' => $now + 86400,
        ];

        $token = JWT::encode($payload, $this->jwtSecret, 'HS256');
        $_SESSION['user_id'] = $user['id'];

        return [
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'role_id' => $user['role_id'],
            ],
        ];
    }

    public function currentUser(): ?array
    {
        if (isset($_SESSION['user_id'])) {
            return $this->users->find((int)$_SESSION['user_id']);
        }

        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (preg_match('/Bearer\s+(.*)$/i', $header, $m)) {
            $token = $m[1];
            try {
                $payload = JWT::decode($token, new Key($this->jwtSecret, 'HS256'));
                return [
                    'id' => $payload->sub,
                    'name' => $payload->name,
                    'role_id' => $payload->role,
                ];
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function hash(string $plain): string
    {
        return password_hash($plain, PASSWORD_DEFAULT);
    }

    public function verify(string $plain, string $hash): bool
    {
        return password_verify($plain, $hash);
    }
}

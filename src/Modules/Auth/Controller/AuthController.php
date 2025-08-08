<?php

namespace Modules\Auth\Controller;

use Modules\Auth\Service\AuthService;

class AuthController
{
    public function __construct(private AuthService $auth)
    {
    }

    public function login($request)
    {
        $data = $request->getParsedBody();
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';
        $result = $this->auth->login($email, $password);
        $status = $result['success'] ? 200 : 401;
        return ['status' => $status, 'body' => $result];
    }

    public function me($request)
    {
        $user = $request->getAttribute('user') ?? $this->auth->currentUser();
        if (!$user) {
            return ['status' => 401, 'body' => ['error' => 'unauthorized']];
        }
        return ['status' => 200, 'body' => ['success' => true, 'user' => $user]];
    }
}

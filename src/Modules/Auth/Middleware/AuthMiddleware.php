<?php

namespace Modules\Auth\Middleware;

use Modules\Auth\Service\AuthService;

class AuthMiddleware
{
    public function __construct(private AuthService $auth)
    {
    }

    public function __invoke($request, $handler)
    {
        $path = $request->getUri()->getPath();
        if (str_starts_with($path, '/api/auth/')) {
            return $handler($request);
        }

        $user = $this->auth->currentUser();
        if (!$user) {
            return ['error' => 'unauthorized', 'status' => 401];
        }
        $request = $request->withAttribute('user', $user);
        return $handler($request);
    }
}

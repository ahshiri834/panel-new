<?php

namespace Modules\Auth\Middleware;

use Modules\Auth\Service\AuthService;
use Modules\Permission\Service\PermissionService;

class AuthMiddleware
{
    public function __construct(private AuthService $auth, private PermissionService $perms)
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
          $perm = $request->getAttribute('permission');
        if ($perm && !$this->perms->allow($user, $perm)) {
            return ['error' => 'forbidden', 'status' => 403];
        }
        $request = $request->withAttribute('user', $user);
        return $handler($request);
    }
}

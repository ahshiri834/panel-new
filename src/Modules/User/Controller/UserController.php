<?php

namespace Modules\User\Controller;

use Modules\User\Service\UserService;

class UserController
{
    public function __construct(private UserService $service)
    {
    }

    public function show($request, $id)
    {
        $user = $this->service->get((int)$id);
        if (!$user) {
            return ['status' => 404, 'body' => ['success' => false, 'message' => 'not found', 'data' => null]];
        }
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'ok', 'data' => $user]];
    }

    public function store($request)
    {
        $data = $request->getParsedBody();
        $id = $this->service->register($data);
        return ['status' => 201, 'body' => ['success' => true, 'message' => 'created', 'data' => ['id' => $id]]];
    }

    public function update($request, $id)
    {
        $data = $request->getParsedBody();
        $this->service->update((int)$id, $data);
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'updated', 'data' => null]];
    }

    public function assignRole($request, $id)
    {
        $data = $request->getParsedBody();
        $this->service->assignRole((int)$id, (int)($data['role_id'] ?? 0));
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'updated', 'data' => null]];
    }
}

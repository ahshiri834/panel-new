<?php

namespace Modules\Role\Controller;

use Modules\Role\Service\RoleService;

class RoleController
{
    public function __construct(private RoleService $service)
    {
    }

    public function index($request)
    {
        $roles = $this->service->list();
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'ok', 'data' => $roles]];
    }

    public function store($request)
    {
        $data = $request->getParsedBody();
        $id = $this->service->create($data['name'] ?? '');
        return ['status' => 201, 'body' => ['success' => true, 'message' => 'created', 'data' => ['id' => $id]]];
    }

    public function assignPermission($request, $id)
    {
        $data = $request->getParsedBody();
        $this->service->assignPermission((int)$id, (int)($data['permission_id'] ?? 0));
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'updated', 'data' => null]];
    }

    public function permissions($request, $id)
    {
        $perms = $this->service->permissions((int)$id);
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'ok', 'data' => $perms]];
    }
}

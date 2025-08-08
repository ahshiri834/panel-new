<?php

namespace Modules\Permission\Controller;

use Modules\Permission\Service\PermissionService;

class PermissionController
{
    public function __construct(private PermissionService $service)
    {
    }

    public function index($request)
    {
        $perms = $this->service->list();
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'ok', 'data' => $perms]];
    }

    public function store($request)
    {
        $data = $request->getParsedBody();
        $id = $this->service->create($data['name'] ?? '');
        return ['status' => 201, 'body' => ['success' => true, 'message' => 'created', 'data' => ['id' => $id]]];
    }
}

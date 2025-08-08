<?php

namespace Modules\Customer\Controller;

use Modules\Customer\Service\CustomerService;

class CustomerController
{
    public function __construct(private CustomerService $service)
    {
    }

    public function index($request)
    {
        $query = $request->getQueryParams();
        $page = (int)($query['page'] ?? 1);
        $search = $query['search'] ?? '';
        $data = $this->service->list($page, $search);
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'ok', 'data' => $data]];
    }

    public function show($request, $id)
    {
        try {
            $customer = $this->service->get((int)$id);
            return ['status' => 200, 'body' => ['success' => true, 'message' => 'ok', 'data' => $customer]];
        } catch (\RuntimeException $e) {
            return ['status' => 404, 'body' => ['success' => false, 'message' => $e->getMessage(), 'data' => null]];
        }
    }

    public function store($request)
    {
        $data = $request->getParsedBody();
        $user = $request->getAttribute('user') ?? [];
        $id = $this->service->create($data, (int)($user['id'] ?? 0));
        return ['status' => 201, 'body' => ['success' => true, 'message' => 'created', 'data' => ['id' => $id]]];
    }

    public function update($request, $id)
    {
        $data = $request->getParsedBody();
        $this->service->update((int)$id, $data);
        return ['status' => 200, 'body' => ['success' => true, 'message' => 'updated', 'data' => null]];
    }

    public function transfer($request)
    {
        $data = $request->getParsedBody();
        try {
            $id = $this->service->transferIfEligible($data['mobile'] ?? '', (int)($data['newOwner'] ?? 0));
            return ['status' => 200, 'body' => ['success' => true, 'message' => 'ok', 'data' => ['id' => $id]]];
        } catch (\RuntimeException $e) {
            return ['status' => 400, 'body' => ['success' => false, 'message' => $e->getMessage(), 'data' => null]];
        }
    }
}

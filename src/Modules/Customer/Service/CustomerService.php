<?php

namespace Modules\Customer\Service;

use Modules\Customer\Repository\CustomerRepository;
use DateTime;

class CustomerService
{
    public function __construct(private CustomerRepository $repo)
    {
    }

    public function get(int $id): array
    {
        $customer = $this->repo->find($id);
        if (!$customer) {
            throw new \RuntimeException('Customer not found');
        }
        return $customer;
    }

    public function list(int $page, string $search = ''): array
    {
        $limit = 20;
        $offset = ($page - 1) * $limit;
        return $this->repo->list($offset, $limit, $search);
    }

    public function create(array $dto, int $userId): int
    {
        return $this->repo->create($dto, $userId);
    }

    public function update(int $id, array $dto): void
    {
        $existing = $this->get($id);
        $changes = [];
        foreach ($dto as $k => $v) {
            if (array_key_exists($k, $existing) && $existing[$k] !== $v) {
                $changes[$k] = $v;
            }
        }
        $this->repo->update($id, $changes);
    }

    public function transferIfEligible(string $mobile, int $newOwner): int
    {
        $doctor = $this->repo->findByMobile($mobile);
        if (!$doctor) {
            return $this->repo->create([
                'doctor_type' => 'Male',
                'name' => $mobile,
                'mobile' => $mobile,
            ], $newOwner);
        }

        if (($doctor['created_by'] ?? 0) == $newOwner) {
            return $doctor['id'];
        }

        $last = $this->repo->getLastPurchaseDate($doctor['id']);
        $threshold = new DateTime('-45 days');
        if (!$last || $last < $threshold) {
            $this->repo->transfer($doctor['id'], $newOwner);
            return $doctor['id'];
        }

        throw new \RuntimeException('Transfer not allowed within 45 days');
    }
}

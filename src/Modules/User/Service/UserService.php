<?php

namespace Modules\User\Service;

use Modules\User\Repository\UserRepository;

class UserService
{
    public function __construct(private UserRepository $users)
    {
    }

    public function register(array $data): int
    {
        return $this->users->create($data);
    }

    public function get(int $id): ?array
    {
        return $this->users->find($id);
    }

    public function update(int $id, array $data): bool
    {
        return $this->users->update($id, $data);
    }

    public function assignRole(int $userId, int $roleId): bool
    {
        return $this->users->assignRole($userId, $roleId);
    }
}

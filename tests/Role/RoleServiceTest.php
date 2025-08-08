<?php

use Modules\Role\Service\RoleService;
use Modules\Role\Repository\RoleRepository;
use PHPUnit\Framework\TestCase;

class RoleServiceTest extends TestCase
{
    public function testCreateAndAssign(): void
    {
        $repo = new class extends RoleRepository {
            public array $roles = [];
            public array $assigned = [];
            public function __construct() {}
            public function create(string $name): int { $id = count($this->roles) + 1; $this->roles[$id] = ['id'=>$id,'name'=>$name]; return $id; }
            public function all(): array { return array_values($this->roles); }
            public function assignPermission(int $roleId,int $permissionId): bool { $this->assigned[] = [$roleId,$permissionId]; return true; }
            public function permissions(int $roleId): array { return []; }
            public function find(int $id): ?array { return $this->roles[$id] ?? null; }
        };
        $service = new RoleService($repo);
        $id = $service->create('editor');
        $this->assertSame(1, $id);
        $service->assignPermission(1, 5);
        $this->assertSame([[1,5]], $repo->assigned);
    }
}

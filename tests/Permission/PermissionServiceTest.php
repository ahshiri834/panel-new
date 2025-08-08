<?php

use Modules\Permission\Service\PermissionService;
use Modules\Permission\Repository\PermissionRepository;
use PHPUnit\Framework\TestCase;

class PermissionServiceTest extends TestCase
{
    public function testCreateAndList(): void
    {
        $repo = new class extends PermissionRepository {
            public array $perms = [];
            public function __construct() {}
            public function create(string $name): int { $id = count($this->perms) + 1; $this->perms[$id] = ['id'=>$id,'name'=>$name]; return $id; }
            public function all(): array { return array_values($this->perms); }
            public function find(int $id): ?array { return $this->perms[$id] ?? null; }
        };
        $service = new PermissionService($repo);
        $id = $service->create('user.view');
        $this->assertSame(1, $id);
        $list = $service->list();
        $this->assertCount(1, $list);
    }
}

<?php

use Modules\Customer\Service\CustomerService;
use Modules\Customer\Repository\CustomerRepository;
use PHPUnit\Framework\TestCase;

class CustomerServiceTest extends TestCase
{
    public function testCreateAndRetrieve(): void
    {
        $repo = new class extends CustomerRepository {
            public array $data = [];
            public function __construct() {}
            public function create(array $data, int $createdBy): int { $data['id'] = 1; $this->data[1] = $data; return 1; }
            public function find(int $id): ?array { return $this->data[$id] ?? null; }
        };
        $service = new CustomerService($repo);
        $id = $service->create(['doctor_type' => 'Male', 'name' => 'Doc', 'mobile' => '123'], 9);
        $this->assertSame(1, $id);
        $customer = $service->get(1);
        $this->assertSame('Doc', $customer['name']);
    }

    public function testUpdateDetectsChanges(): void
    {
        $repo = new class extends CustomerRepository {
            public array $data = [1 => ['id' => 1, 'name' => 'Old', 'mobile' => '123', 'doctor_type' => 'Male']];
            public array $updated = [];
            public function __construct() {}
            public function find(int $id): ?array { return $this->data[$id] ?? null; }
            public function update(int $id, array $fields): bool { $this->updated = $fields; return true; }
        };
        $service = new CustomerService($repo);
        $service->update(1, ['name' => 'New', 'mobile' => '123']);
        $this->assertSame(['name' => 'New'], $repo->updated);
    }

    public function testTransferLogic(): void
    {
        // new customer
        $repoNew = new class extends CustomerRepository {
            public function __construct() {}
            public function findByMobile(string $mobile): ?array { return null; }
            public function create(array $data, int $createdBy): int { return 5; }
        };
        $serviceNew = new CustomerService($repoNew);
        $id = $serviceNew->transferIfEligible('555', 2);
        $this->assertSame(5, $id);

        // within 45 days should throw
        $repoRecent = new class extends CustomerRepository {
            public function __construct() {}
            public function findByMobile(string $mobile): ?array { return ['id' => 1, 'created_by' => 1]; }
            public function getLastPurchaseDate(int $id): ?DateTime { return new DateTime('-30 days'); }
        };
        $serviceRecent = new CustomerService($repoRecent);
        $this->expectException(\RuntimeException::class);
        $serviceRecent->transferIfEligible('555', 2);

        // beyond 45 days transfers
        $repoOld = new class extends CustomerRepository {
            public bool $transferred = false;
            public function __construct() {}
            public function findByMobile(string $mobile): ?array { return ['id' => 1, 'created_by' => 1]; }
            public function getLastPurchaseDate(int $id): ?DateTime { return new DateTime('-60 days'); }
            public function transfer(int $id, int $newOwner): bool { $this->transferred = true; return true; }
        };
        $serviceOld = new CustomerService($repoOld);
        $id = $serviceOld->transferIfEligible('555', 2);
        $this->assertTrue($repoOld->transferred);
        $this->assertSame(1, $id);
    }
}

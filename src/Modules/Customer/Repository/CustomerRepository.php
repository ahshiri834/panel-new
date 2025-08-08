<?php

namespace Modules\Customer\Repository;

use PDO;
use DateTime;

class CustomerRepository
{
    public function __construct(private PDO $db)
    {
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM doctor_info WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function findByMobile(string $mobile): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM doctor_info WHERE mobile = :mobile');
        $stmt->execute(['mobile' => $mobile]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ?: null;
    }

    public function create(array $data, int $createdBy): int
    {
        $data['created_by'] = $createdBy;
        $columns = ['doctor_type','name','mobile','address','specialty','national_id','created_by','type_add'];
        $cols = [];
        $params = [];
        foreach ($columns as $col) {
            if (isset($data[$col])) {
                $cols[] = $col;
                $params[":$col"] = $data[$col];
            }
        }
        $sql = 'INSERT INTO doctor_info (' . implode(',', $cols) . ')
                VALUES (' . implode(',', array_keys($params)) . ')';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $fields): bool
    {
        if (!$fields) {
            return true;
        }
        $sets = [];
        $params = [];
        foreach ($fields as $k => $v) {
            $sets[] = "$k = :$k";
            $params[":$k"] = $v;
        }
        $params[':id'] = $id;
        $sql = 'UPDATE doctor_info SET ' . implode(',', $sets) . ' WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function transfer(int $id, int $newOwner): bool
    {
        $stmt = $this->db->prepare('UPDATE doctor_info SET created_by = :owner WHERE id = :id');
        return $stmt->execute(['owner' => $newOwner, 'id' => $id]);
    }

    public function getLastPurchaseDate(int $id): ?DateTime
    {
        $sql = "SELECT MAX(created_at) AS last FROM invoices_new WHERE doctor_id = :id AND approval_summary IN ('تایید نهایی','ارسال کالا','منتظر تایید حسابداری')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row || !$row['last']) {
            return null;
        }
        return new DateTime($row['last']);
    }

    public function list(int $offset, int $limit, string $search = ''): array
    {
        $params = ['limit' => $limit, 'offset' => $offset];
        $sql = 'SELECT * FROM doctor_info';
        if ($search) {
            $sql .= ' WHERE name LIKE :search OR mobile LIKE :search';
            $params['search'] = "%$search%";
        }
        $sql .= ' ORDER BY id DESC LIMIT :limit OFFSET :offset';
        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $type = $k === 'limit' || $k === 'offset' ? PDO::PARAM_INT : PDO::PARAM_STR;
            $stmt->bindValue($k, $v, $type);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

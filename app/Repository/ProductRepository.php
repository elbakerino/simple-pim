<?php declare(strict_types=1);

namespace App\Repository;

use \App\Services\DynamoService;

class ProductRepository {
    protected DynamoService $dynamo;
    protected string $table;

    public function __construct(DynamoService $dynamo, string $table) {
        $this->dynamo = $dynamo;
        $this->table = $table;
    }
}

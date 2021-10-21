<?php declare(strict_types=1);

namespace App\Repository;

use \App\Services\DynamoService;
use Ramsey\Uuid\Uuid;

class CategoryRepository {
    protected DynamoService $dynamo;
    protected string $table;

    public function __construct(DynamoService $dynamo, string $table) {
        $this->dynamo = $dynamo;
        $this->table = $table;
    }

    protected function timestamp(): string {
        return (new \DateTime('now', new \DateTimeZone('UTC')))->format('Y-m-d H:i:s.u');
    }

    protected function padPosition(int $position): string {
        $position_str = (string)$position;
        $position_str = str_pad($position_str, 6, '0');
        $position_str = '1' . $position_str;
        return $position_str;
    }

    protected function makeCategory(string $name, ?string $parent, int $position, \stdClass $labels): array {
        $ts = $this->timestamp();
        $uuid = Uuid::uuid4()->toString();
        $pos_str = $this->padPosition($position);
        return [
            'uuid' => 'category#' . $uuid,
            'data_key' => 'category#' . $uuid,
            'sort_key' => 'category#' . $pos_str . '#' . $uuid,
            'category_uuid' => $uuid,
            'category_parent' => $parent ?? 'root',
            'category_pk' => $uuid,
            'name' => $name,
            'created_at' => $ts,
            'updated_at' => $ts,
            'labels' => $labels,
        ];
    }

    public function create(string $name, ?string $parent, int $position, \stdClass $labels) {
        $category_raw = $this->makeCategory($name, $parent, $position, $labels);
        $item = $this->dynamo->arrayToItem($category_raw);
        $response = $this->dynamo->client()->putItem([
            'TableName' => $this->table,
            'Item' => $item,
            'ConditionExpression' => '#uuid <> :uuid__val AND #data_key <> :data_key__val',
            'ExpressionAttributeNames' => [
                '#uuid' => 'uuid',
                '#data_key' => 'data_key',
            ],
            'ExpressionAttributeValues' => [
                ':uuid__val' => $item['uuid'],
                ':data_key__val' => $item['data_key'],
            ],
            'ReturnValues' => 'NONE',// default for put item
            //'ReturnValues' => 'ALL_OLD',
        ]);
        if($response->offsetGet('@metadata')['statusCode'] === 200) {
            return $category_raw;
        }

        throw new \RuntimeException('Category create error, DB status: ' . $response->offsetGet('@metadata')['statusCode']);
    }

    public function listByParent(string $parent, string $direction, int $per_page): array {
        $query = [
            'IndexName' => 'categories_by_parent',
            'KeyConditionExpression' => '#uuid__key = :uuid__val',
            'ScanIndexForward' => $direction !== 'desc',// `true` = `asc`, `false` = `desc`
            'ExpressionAttributeNames' => [
                '#uuid__key' => 'category_parent',
            ],
            'ExpressionAttributeValues' => [
                ':uuid__val' => ['S' => $parent],
            ],
            'Limit' => $per_page,
            'TableName' => $this->table,
        ];

        $res = $this->dynamo->client()->query($query);
        $items = $res->offsetGet('Items');

        return [
            'size' => (int)$res->offsetGet('@metadata')['headers']['content-length'],
            'per_page' => $per_page,
            'last_evaluated_key' => $res->offsetGet('LastEvaluatedKey') ? $this->dynamo->itemToArray($res->offsetGet('LastEvaluatedKey')) : null,
            // todo: also decode `labels` here, stored as JSON string in DynamoDB
            'items' => array_map([$this->dynamo, 'itemToArray'], $items),
        ];
    }

    public function getDetails(string $uuid): array {
        $query = [
            'KeyConditionExpression' => '#uuid__key = :uuid__val',
            'ExpressionAttributeNames' => [
                '#uuid__key' => 'uuid',
            ],
            'ExpressionAttributeValues' => [
                ':uuid__val' => ['S' => 'category#' . $uuid],
            ],
            'TableName' => $this->table,
        ];
        $res = $this->dynamo->client()->query($query);
        $items = $res->offsetGet('Items');
        if(!isset($items[0])) {
            throw new \RuntimeException('db entry not found');
        }

        return [
            'size' => (int)$res->offsetGet('@metadata')['headers']['content-length'],
            // todo: also decode `labels` here, stored as JSON string in DynamoDB
            'items' => $this->dynamo->itemToArray($items[0]),
        ];
    }

    public function update(string $uuid) {
        throw new \RuntimeException('not implemented');
    }

    public function delete(string $uuid) {
        throw new \RuntimeException('not implemented');
    }
}

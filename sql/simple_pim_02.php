<?php declare(strict_types=1);

return static function() {
    return [
        'TableName' => 'simple_pim',
        'BillingMode' => 'PAY_PER_REQUEST',
        //'BillingMode' => 'PROVISIONED',
        'KeySchema' => [
            ['AttributeName' => 'uuid', 'KeyType' => 'HASH',],
            ['AttributeName' => 'data_key', 'KeyType' => 'RANGE',],
        ],
        'AttributeDefinitions' => [
            ['AttributeName' => 'uuid', 'AttributeType' => 'S',],
            ['AttributeName' => 'data_key', 'AttributeType' => 'S',],
            ['AttributeName' => 'sort_key', 'AttributeType' => 'S',],
            ['AttributeName' => 'category_uuid', 'AttributeType' => 'S',],
            ['AttributeName' => 'category_parent', 'AttributeType' => 'S',],
            ['AttributeName' => 'product_uuid', 'AttributeType' => 'S',],
            ['AttributeName' => 'category_pk', 'AttributeType' => 'S',],
        ],
        /*'ProvisionedThroughput' => [
            'ReadCapacityUnits' => 2,
            'WriteCapacityUnits' => 2,
        ],*/
        'GlobalSecondaryIndexes' => [
            [
                'IndexName' => 'categories',
                'KeySchema' => [
                    ['AttributeName' => 'category_uuid', 'KeyType' => 'HASH',],
                    ['AttributeName' => 'data_key', 'KeyType' => 'RANGE',],
                ],
                'Projection' => [
                    'ProjectionType' => 'ALL',
                ],
            ], [
                'IndexName' => 'categories_by_parent',
                'KeySchema' => [
                    ['AttributeName' => 'category_parent', 'KeyType' => 'HASH',],
                    ['AttributeName' => 'sort_key', 'KeyType' => 'RANGE',],
                ],
                'Projection' => [
                    'ProjectionType' => 'ALL',
                ],
            ], [
                'IndexName' => 'products',
                'KeySchema' => [
                    ['AttributeName' => 'product_uuid', 'KeyType' => 'HASH',],
                    ['AttributeName' => 'data_key', 'KeyType' => 'RANGE',],
                ],
                'Projection' => [
                    'ProjectionType' => 'ALL',
                ],
            ], [
                'IndexName' => 'category_lists',
                'KeySchema' => [
                    ['AttributeName' => 'category_pk', 'KeyType' => 'HASH',],
                    ['AttributeName' => 'sort_key', 'KeyType' => 'RANGE',],
                ],
                'Projection' => [
                    'ProjectionType' => 'ALL',
                ],
            ],
        ],
    ];
};

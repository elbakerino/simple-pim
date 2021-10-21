-- @lang PostgresSQL

DROP TABLE IF EXISTS product;

CREATE TABLE product
(
    id         UUID      NOT NULL DEFAULT uuid_generate_v1(),
    CONSTRAINT product_id PRIMARY KEY (id),
    language   varchar   NOT NULL,
    name       varchar   NOT NULL,
    status     varchar   NOT NULL,
    created_at timestamp NOT NULL,
    updated_at timestamp NOT NULL,
    data       jsonb     NOT NULL
);

CREATE INDEX CONCURRENTLY "product.id"
    on product using brin (id);

-- @lang PostgresSQL

DROP TABLE IF EXISTS products_to_category;

CREATE TABLE products_to_category
(
    category UUID NOT NULL,
    product  UUID NOT NULL
);

CREATE INDEX CONCURRENTLY "products_to_category.category"
    on products_to_category using brin (category);

CREATE INDEX CONCURRENTLY "products_to_category.product"
    on products_to_category using brin (product);

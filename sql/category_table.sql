-- @lang PostgresSQL

DROP TABLE IF EXISTS category;

CREATE TABLE category
(
    id       UUID    NOT NULL DEFAULT uuid_generate_v1(),
    CONSTRAINT category_id PRIMARY KEY (id),
    parent   UUID,
    position int     NOT NULL,
    name     varchar NOT NULL,
    labels   jsonb   NOT NULL
);

CREATE INDEX CONCURRENTLY "category.id"
    on category using brin (id);

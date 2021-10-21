-- @lang PostgresSQL

SELECT c.name, c.position
FROM category c
ORDER BY c.position;

SELECT c.name, count(p.id) as products
FROM category c
         INNER JOIN products_to_category p2c on p2c.category = c.id
         INNER JOIN product p on p.id = p2c.product
GROUP BY 1, c.position
ORDER BY c.position;

SELECT c.name, json_agg(json_build_object('id', p.id, 'name', p.name)) as product_ids
FROM category c
         INNER JOIN products_to_category p2c on p2c.category = c.id
         INNER JOIN product p on p.id = p2c.product
GROUP BY 1, c.position
ORDER BY c.position;

SELECT *
FROM product
where name = 'Surfboard Nature';

SELECT id, name, created_at, updated_at
FROM product
where language = 'en'
ORDER BY updated_at desc;

# Simple Pim specific setup

## Initial Setup

Clean project start:

```bash
mkdir simple-pim

# run extra composer container on windows:
docker run -it --rm -v %cd%/simple-pim:/app composer create-project orbiter/satellite-app .
# run extra composer container on unix:
docker run -it --rm -v `pwd`/simple-pim:/app composer create-project orbiter/satellite-app .

cd simple-pim

docker run -it --rm -v %cd%:/app composer require aws/aws-sdk-php-resources
docker run -it --rm -v %cd%:/app composer require ramsey/uuid
```

Install after clone:

```bash
cd simple-pim

# run extra composer container on windows:
docker run -it --rm -v %cd%:/app composer install .
# run extra composer container on unix:
docker run -it --rm -v `pwd`:/app composer install .

touch .env
```

## Start

```bash
docker-compose up
```

Endpoints:

- App: [http://localhost:3333](http://localhost:3333)
- DynamoDB / Scylla: [http://localhost:3334](http://localhost:3334)
- Swagger:
    - UI: [http://localhost:3335](http://localhost:3335)
    - `swagger.json`: [http://localhost:3333/swagger.json](http://localhost:3333/swagger.json) uses: `./web/swagger.json`
- PostgreSQL: [http://localhost:3336](http://localhost:3336)
    - DB name: `db`
    - User: `db_user`
    - Password: `db_pass`

DynamoDB table helper:

- Visualizer: [dynamodb-visualizer.bemit.codes](https://dynamodb-visualizer.bemit.codes)
- Scan endpoint: `http://localhost:3333/dynamo-scan` [link](http://localhost:3333/dynamo-scan?table=simple_pim)

Check PHP CLI:

```bash
docker-compose run --rm worker php cli -h
```

## Maintenance

Clean docker volumes / DBs:

```bash
docker-compose down
docker volume rm simple-pim_pg_primary
docker-compose up --build
```

## DynamoDB

```bash
# start persisting container for a bit faster runs:
docker-compose run --rm worker sh

php cli dynamo:create simple_pim_01
php cli dynamo:seed simple_pim_01
php cli dynamo:inspect-dump simple_pim

# maintenance: recreate table:
php cli dynamo:delete simple_pim && php cli dynamo:create simple_pim_01 && php cli dynamo:seed simple_pim_01 && php cli dynamo:inspect-dump simple_pim

php cli dynamo:delete simple_pim && php cli dynamo:create simple_pim_02 && php cli dynamo:seed simple_pim_02 && php cli dynamo:inspect-dump simple_pim
```

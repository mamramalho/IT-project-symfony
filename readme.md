# Symfony Docker

> Docker Compose for Symfony

## Installation

1 - Start the docker compose

```bash
docker-compose up -d
```

**Note:** First time will take a few minutes to download all the containers.

2 - Install all the dependencies here:

```bash
docker-compose exec php /bin/bash
```

or

```bash
docker-compose exec php sh
```

3 - Change your .env mysql credentials on `app/.env`

```dotenv
DATABASE_URL="mysql://user:password@mysql:3306/app?serverVersion=8&charset=utf8mb4"
```

## Run the application

The application is listening the port :8888.
You can see the application running in **[http://localhost:8888](http://localhost:8888)**

## Commands

Check all the containers

```bash
docker-compose ps
```

Entering the container

```bash
docker-composer exec php /bin/sh
```

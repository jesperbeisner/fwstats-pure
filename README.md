# FWSTATS 🚀

Statistics website for the worlds _ActionFreewar_ and _ChaosFreewar_ in the browser game [freewar.de](https://www.freewar.de).

## Setup

### Local

#### 1. Start the docker container

```bash
docker-compose up -d
```

#### 2. Install composer packages

Use the docker container to install composer packages. This way it's not important which PHP version you have installed locally, and you do not run into any errors.

```bash
docker-compose exec php composer install
```

#### 3. Load migrations

```bash
docker-compose exec php php bin/console.php app:database-migration
```

#### 4. Load fixtures

```bash
docker-compose exec php php bin/console.php app:database-fixture
```

#### 5. Finished

Open your browser and visit http://localhost:8080. A test account with e-mail `admin@example.com` and password `password12345` was also created for you.


### Prod

```bash
# Create prod config and change needed values
cp ./.env.local.php.dist ./.env.local.php

# Docker
docker-compose -f docker-compose.prod.yml up -d --build

# Install composer packages
docker-compose exec php composer install --no-dev --optimize-autoloader --no-interaction

# Load migrations
docker-compose exec php php bin/console.php app:database-migration

# Run one cronjob for everything every 5 minutes
*/5 * * * * docker exec fwstats-php-prod php bin/console.php app:run > /dev/null 2>&1 
```

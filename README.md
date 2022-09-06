# fwstats.de

## Setup

❗ Docker needs to be installed on your machine ❗

### Local

```bash
# Start Docker Containers
docker-compose up -d

# Install composer packages
docker-compose exec php composer install

# Load migrations
docker-compose exec php php bin/console.php app:database-migration

# Load test data
docker-compose exec php php bin/console.php app:database-fixture

# Visit http://localhost:8080

# Test-Account-Mail: test@test.com
# Test-Account-Password: Password123
```

### Prod

```bash
# Create prod config and change needed values
cp ./config/config.local.php.dist ./config/config.local.php

# Docker
docker-compose -f docker-compose.prod.yml up -d --build

# Install composer packages
docker-compose exec php composer install --no-dev --optimize-autoloader --no-interaction

# Load migrations
docker-compose exec php php bin/console.php app:database-migration

# Run one cronjob for everything every 5 minutes
*/5 * * * * docker exec fwstats-php-prod php bin/console.php app:run > /dev/null 2>&1 
```

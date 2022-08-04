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

# Visit http://localhost:8080
```

### Prod

```bash
# Docker
docker-compose -f docker-compose.prod.yml up -d --build

# Cronjob
*/5 * * * * docker exec fwstats-php-prod php bin/console.php app:import-players > /dev/null 2>&1 
```

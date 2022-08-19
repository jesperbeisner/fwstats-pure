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

# Load data
docker-compose exec php php bin/console.php app:import-world-stats

# Visit http://localhost:8080
```

### Prod

```bash
# Docker
docker-compose -f docker-compose.prod.yml up -d --build

# Install composer packages
docker-compose exec php composer install --no-dev --optimize-autoloader --no-interaction

# Load migrations
docker-compose exec php php bin/console.php app:database-migration

# World Stats Cronjob
*/5 * * * * docker exec fwstats-php-prod php bin/console.php app:import-world-stats > /dev/null 2>&1 

# Images Cronjob
1,6,11,16,21,26,31,36,41,46,51,56 * * * * docker exec fwstats-php-prod php bin/console.php app:create-images > /dev/null 2>&1 

# Player Seconds Cronjob
0 0 * * * docker exec fwstats-php-prod php bin/console.php app:player-active-seconds > /dev/null 2>&1 
```

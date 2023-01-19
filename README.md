# FWSTATS 🚀

Statistics website for the worlds _ActionFreewar_ and _ChaosFreewar_ in the browser game [freewar.de](https://www.freewar.de).

## TechStack

PHP 8.2, Bulma CSS, SQLite, Vanilla JS, Apache httpd with mod_php. All of this is shipped as a single docker image. 

## Quality assurance

- PHPUnit: Unit tests and application tests against a database
- PHPStan: Level max, 100% type coverage, strict rules + own custom rules
- PHP-CS-Fixer: PSR-12

## Deployment strategy

Push to the master branch runs a GitHub action which itself runs the full testsuite (PHP-CS-Fixer, PHPUnit, PHPStan) and when everything works, builds a new container image and pushes this image to the GitHub container registry.
After this I will just stop the running container on my server and start a new one.
No rolling update needed, not like I have tons of users. 🤷‍♂️

## Local setup

### 1. Start the docker container

```bash
docker-compose up -d
```

### 2. Install composer packages

Use the docker container to install composer packages. This way it's not important which PHP version you have installed locally, and you do not run into any errors.

```bash
docker-compose exec php composer install
```

### 3. Load migrations

```bash
docker-compose exec php php bin/console.php app:database-migration
```

### 4. Load fixtures

```bash
docker-compose exec php php bin/console.php app:database-fixture
```

### 5. Finished

Open your browser and visit http://localhost:8080. A test account with e-mail `admin@example.com` and password `password12345` was also created for you.


## Deployment

```bash
TODO: Write the deployment steps. Create volume, run image...

# Run one cronjob for everything every 5 minutes
*/5 * * * * docker exec fwstats-php-prod php bin/console.php app:run > /dev/null 2>&1 
```

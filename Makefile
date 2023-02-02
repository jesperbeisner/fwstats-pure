.PHONY: help
help: ## Show this help
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_\-\.]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

test: ## Runs CS-Fixer, PHPUnit and PHPStan
	docker-compose exec php composer test

csfixer: ## Run CS-Fixer
	docker-compose exec php composer csfixer

phpunit: ## Run PHPUnit
	docker-compose exec php composer phpunit

phpunit-application: ## Run PHPUnit with the Application testsuite
	docker-compose exec php composer phpunit -- --testsuite Application

phpunit-functional: ## Run PHPUnit with the Functional testsuite
	docker-compose exec php composer phpunit -- --testsuite Functional

phpunit-unit: ## Run PHPUnit with the Unit testsuite
	docker-compose exec php composer phpunit -- --testsuite Unit

phpstan: ## Run PHPStan
	docker-compose exec php composer phpstan

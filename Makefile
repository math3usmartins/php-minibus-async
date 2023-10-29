.PHONY: php-cs-fix
php-cs-fix:
	docker compose run --rm php \
		vendor/bin/php-cs-fixer \
			fix \
			-vvv

.PHONY: test
test:
	docker compose run --rm php vendor/bin/phpunit

.PHONY: phpstan
phpstan:
	docker compose run --rm php vendor/bin/phpstan analyse \
		-c phpstan.neon \
		-- src test

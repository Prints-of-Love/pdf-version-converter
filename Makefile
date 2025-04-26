.PHONY: test build

test:
	./vendor/bin/phpunit -c tests

build:
	composer install
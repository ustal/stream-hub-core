IMAGE_NAME = stream-hub-core

build:
	docker build -t $(IMAGE_NAME) .

test: build
	docker run --rm -v $(PWD):/var/www/library -w /var/www/library $(IMAGE_NAME) vendor/bin/phpunit

ash: build
	docker run -it --rm -v $(PWD):/var/www/library -w /var/www/library $(IMAGE_NAME) ash

debug: build
	docker run -it --rm -e PHP_IDE_CONFIG=serverName=stream-hub-core -v $(PWD):/var/www/library $(IMAGE_NAME) ash

clean:
	docker rmi $(IMAGE_NAME) || true

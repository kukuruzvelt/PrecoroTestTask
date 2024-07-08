start:
	composer install
	docker compose up -d
	./infrastrucutre/wait-for-db.sh
	bin/console doctrine:migrations:migrate -n
	bin/console doctrine:fixtures:load -n
	symfony server:start -d

stop:
	docker compose stop
	symfony server:stop
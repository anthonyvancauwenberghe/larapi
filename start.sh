cd ../laradock && docker-compose up -d nginx redis mongo mariadb phpmyadmin workspace php-worker && docker-compose exec --user=laradock workspace bash

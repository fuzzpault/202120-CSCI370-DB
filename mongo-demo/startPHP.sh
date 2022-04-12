#!/bin/bash
docker run -i -t -p 80:80 -p 3306:3306 -v ${PWD}/app:/app -v ${PWD}/mysql:/var/lib/mysql -v ${PWD}/logs:/var/log/apache2 fuzzpault/php-mysql-nosql-php
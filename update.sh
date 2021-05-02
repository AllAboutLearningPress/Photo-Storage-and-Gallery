#!/bin/bash
docker exec -it app composer install
docker exec -it app php artisan key:generate
docker exec -it app php artisan optimize

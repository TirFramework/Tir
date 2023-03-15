# crm

# install

## local

docker-compose -f "docker-compose.local.yml" up -d

## product

docker-compose -f "docker-compose.prod.yml" up -d

cat app/start.sh | sed 's/\r$//' >app/newstart.sh && rm app/start.sh && mv app/newstart.sh app/start.sh

for fitst time

docker exec tir-app composer install

docker compose -f "docker-compose.local.yml" run --rm -p "3015:3000" node bash

docker compose -f "docker-compose.local.yml" run --rm node npm i

docker compose -f "docker-compose.local.yml" run --rm -p "3015:3000" node npm run start

php artisan vendor:publish --tag=mehr-panel --force

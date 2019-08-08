#!/bin/bash

php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create

rm src/Migrations/Version2019*.*

php bin/console make:migration
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction --group=MenuItemFixtures
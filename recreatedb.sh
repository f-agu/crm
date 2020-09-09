#!/bin/bash

php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create

rm src/Migrations/Version2019*.*
rm src/Migrations/Version202*.*
rm src/Migrations/Version203*.*

php bin/console make:migration
php bin/console doctrine:migrations:migrate --no-interaction
php bin/console doctrine:fixtures:load --no-interaction --group=MenuItemFixtures
php bin/console cache:clear

echo ''
echo 'To add fake values :'
echo ''
echo 'php bin/console doctrine:fixtures:load --append --group=ClubsFixtures --group=AccountUserFixtures --group=UserClubLinkFixtures'
echo ''
echo ''
echo ''
echo 'To migrate real values :'
echo ''
echo 'php bin/console crm:migration --domainname=legacydomain.fr --dump=sql/dump_src.sql'
echo ''

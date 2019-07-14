Pr�requis / � installer
=======================

# Pour Windows

(Respecter l'ordre)

Git : https://git-scm.com/download
Wamp : http://www.wampserver.com/
Composer : https://getcomposer.org/download/
Symfony : https://symfony.com/download
Node.js : https://nodejs.org/en/download/
Yarn : https://yarnpkg.com/fr/docs/install
ER/Builder : https://soft-builder.com/en/downloads/ERBuilder_Install.zip



Projet test
============


# git config --global user.email "ton-email"
# git config --global user.name "ton-nom"

Dans un dossier temporaire (ici : D:\tmp\web\symfony)
# symfony new --full my_project
# cd my_project

# symfony server:start
# composer require symfony/apache-pack
>> [a]

Dans le dossier de Wamp64, supprimer le dosier www
# mklink /D www D:\tmp\web\symfony\my_project\public



# composer require annotations
# composer require symfony/orm-pack
# composer require --dev symfony/maker-bundle

-- edit .env

D:\cenaclerm\wamp64\bin\php\php7.2.10\php bin/console doctrine:database:create
D:\cenaclerm\wamp64\bin\php\php7.2.10\php bin/console make:entity
D:\cenaclerm\wamp64\bin\php\php7.2.10\php bin/console make:migration
D:\cenaclerm\wamp64\bin\php\php7.2.10\php bin/console doctrine:migrations:migrate
D:\cenaclerm\wamp64\bin\php\php7.2.10\php bin/console make:controller UserController

# composer require symfony/validator doctrine/annotations

D:\cenaclerm\wamp64\bin\php\php7.2.10\php bin/console cache:clear

# symfony server:start


# yarn install
# yarn add bootstrap --dev
# yarn add jquery popper.js --dev




Install o2sw...
===============

>> connect ssh

R�cup�re les sources
# git clone https://github.com/f-agu/crm.git

R�cup�re les libs
# cd crm/web
# composer install

Initialise la DB
# php bin/console make:migration
# php bin/console doctrine:migrations:migrate
# php bin/console doctrine:fixtures:load --append

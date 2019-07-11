Prérequis / à installer
=======================

# Pour Windows

(Respecter l'ordre)

Git : https://git-scm.com/download
Wamp : http://www.wampserver.com/
Composer : https://getcomposer.org/download/
Symfony : https://symfony.com/download




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


# symfony server:start
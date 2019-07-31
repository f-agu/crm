# Windows developer


## Required softwares

Install the following softwares (keep order) :

- Git : https://git-scm.com/download
- Wamp : http://www.wampserver.com/
- Composer : https://getcomposer.org/download/  (select php 7.2.x)
- Symfony : https://symfony.com/download
- ER/Builder : https://soft-builder.com/en/downloads/ERBuilder_Install.zip
- Eclipse PHP : https://www.eclipse.org/pdt/

If you have a error message like this : "MSVCR110.dll is missing", download the fix from https://www.microsoft.com/fr-FR/download/details.aspx?id=30679


## Prepare your environment

### Sources

In a new folder (ex: c:\projects), with a bash or cmd, retreive the project :

```
git clone https://github.com/f-agu/crm.git
```

Download all libraries :

```
cd crm
composer install
```

Run wamp64.

In the crm folder project, copy the `.env` to `.env.local`. Edit it and replace `DATABASE_URL=...` with :

```
DATABASE_URL=mysql://root:@127.0.0.1:3306/my_db
```

### Configure Wamp

In the wamp64 folder, rename the `www` folder to (for example) `www_origin`.

Create a symbolic link to the crm project : with a `cmd`, in the wamp folder :

```
mklink /D www <path_to_crm_project>\public_html
```

### Initialize the database    

In the crm folder project, with cmd :

```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```

Inspect the result with a browser : http://localhost/phpmyadmin/  (user : root, password : )


## Update your environment

Update the project. In the root folder project, do :

```
git pull
composer install
```

Ensure Wamp64 is running.

Recreate / update the database :

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```


# Update Translations

```
php bin/console translation:update --dump-messages --force fr
php bin/console translation:update --dump-messages --force en
```

# Push to production

In the production server :

```
git pull
composer install
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

# Cache

Sometimes, it's necessary to clear the cache : 

```
php bin/console cache:clear
```


# Swagger

Swagger is available  : http://localhost/swagger/index.html



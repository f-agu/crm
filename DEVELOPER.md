# Windows developer


## Required softwares

Install the following softwares (keep order) :

- Git : https://git-scm.com/download
- Wamp : http://www.wampserver.com/
- Composer : https://getcomposer.org/download/
- Symfony : https://symfony.com/download
- ER/Builder : https://soft-builder.com/en/downloads/ERBuilder_Install.zip


## Prepare your environment

### Sources

In a folder, with a bash, retreive the project :

```
git clone https://github.com/f-agu/crm.git
```

Download all libraries :

```
cd crm
composer install
```

Run wamp64.

In the folder project, copy the `.env` to `.env.local`. Edit it and replace `DATABASE_URL=...` with :

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

```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
```



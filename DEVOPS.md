# Migration from legacy

On "source" database :

```
mysqldump -h127.0.0.1 -p<password> <database> develeve_site develeve_blacklist develeve_cenacle devclub > dump_src.sql
```

On "destination" database :

```
./recreatedb.sh
php bin/console crm:migration --domainname=<legacy-domain-name> --dump=dump_src.sql
```

# To the production server

Ignore `chmod` modification :

```
git config core.fileMode false
```


# Backup database

```
php bin/console db:dump backup
```


# Push to production and database reinitialization

Edit `.env.local` to set `APP_ENV` to `dev`.

```
git pull
composer install
composer update
chmod 750 recreatedb.sh
./recreatedb.sh

# Replace legacydomain.fr by the current domain name

php bin/console crm:migration --domainname=legacydomain.fr --dump=dump_src.sql
```

Restore the `APP_ENV` to `prod` in the file `.env.local`.

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


# Backup database

```
php bin/console db:dump backup
```

# Structure des dossiers

```
/
 ├── assets
 ├── bin
 ├── config   (configuration de Symfony et des composants)
 ├── doc      (documentation pour les développeurs)
 ├── media    (stockage des fichiers uploadé par les utilisateurs, sera renommé)
 ├── node_modules
 ├── public_html   (point d'entrée du site Web)
 ├── src           (sources PHP du site Web)
 │   ├── Command      (script utilisable avec php bin/console ...)
 │   ├── Controller   (points d'entrées des requêtes HTTP pour l'affichage Web)
 │   │   └── Api      (points d'entrées des requêtes HTTP, pas d'affichage, juste des échanges de données)
 │   ├── Dao          (Data Access Object : contient du SQL)
 │   ├── DataFixtures (initialise la DB avec des données)
 │   ├── Emails       (gestion des e-mails)
 │   ├── Entity       (objets fonctionnels du site, structure de la DB)
 │   ├── Exception    (les erreurs possibles)
 │   ├── Media        (gestion des médias dans le dossier /media)
 │   ├── Migrations   (tambouille interne de Symfony pour updater la DB en cas de changements des Entities)
 │   ├── Model        (objet qui transit en HTTP entre le client et le site Web)
 │   ├── Repository   (accéder, modifier les Entities avec des requêtes SQL ou OQL) 
 │   ├── Security     (éléments de sécurité : login, password)
 │   ├── Service      (couche fonctionnel, ex : créer un user)
 │   ├── Util         (des utilitaires)
 │   └── Validator    
 │       └── Constraints   (validation personnalisée des Models)
 ├── templates     (HTML au format twig)
 ├── translations  (traductions)
 └── vendor        (tambouille interne de Symfony, gestion des composants)
```

# Communication par rapport aux dossiers

![schema](/doc/layers-macro.png)

# Dans les grandes lignes

J'utilise Symfony pour simplifier les développements et raccourcir les temps des dev.

Symfony utilise des composants / plugins pour compléter ses fonctionnalités, exemple ici :

 * doctrine : gestion de la DB
 * swiftmailer : envoi d'e-mails
 * monolog : logs
 * ...

Pour ce site, j'en utilise pas mal, je ne vais pas tout énumérer.


## Doctrine

Doctrine simplifie la gestion de la DB. Il est possible d'écrire aucune requête SQL !

### Créer une table

Une table correspond (en général) à une Entity. Donc pour créer un table, il faut une Entity. Pas besoin d'écrire l'Entity à la main, [Symfony aide](https://symfony.com/doc/current/doctrine.html#creating-an-entity-class) :

```
php bin/console make:entity
```

Répondre aux questions, et par magie, le script vient de créer une classe Entity (dans le dossier /src/Entity/)avec tous les champs nécessaires.

Pour appliquer les changements sur la DB :

```
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

La DB a été modifié.


### Modifier une table

```
php bin/console make:entity
```

### Supprimer une table une table

Simplement supprimer l'Entity


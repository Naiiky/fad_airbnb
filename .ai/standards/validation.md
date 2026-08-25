# Validation adaptative Symfony avec Docker

Exécuter depuis la racine du dépôt. L'application est montée de `www/` vers `/var/www/html`; sa racine dans le conteneur est `/var/www/html/skeleton`.

## Détection

1. Lire `docker-compose.yml`, Dockerfile, volumes et `working_dir` éventuel.
2. Confirmer les services avec `docker compose config --services`.
3. Identifier le service applicatif par image/build et montage du code; ici `apache_airbnb`.
4. Déduire la racine applicative du volume; ici `/var/www/html/skeleton`.
5. Vérifier l'état avec `docker compose ps`. Si Docker est indisponible : `UNABLE_TO_VALIDATE`, cause et contrôles non exécutés.

## Exécution centralisée

Préfixe applicatif actuel :

```bash
docker compose exec -T apache_airbnb sh -lc "cd /var/www/html/skeleton && <commande>"
```

Utiliser le nom de service Compose `apache_airbnb`, pas `container_name`, afin de respecter les surcharges de `.env`.

```bash
# Symfony/PHP
docker compose exec -T apache_airbnb sh -lc "cd /var/www/html/skeleton && php bin/console <commande>"
# Composer
docker compose exec -T apache_airbnb sh -lc "cd /var/www/html/skeleton && composer <commande>"
# PHPUnit détecté
docker compose exec -T apache_airbnb sh -lc "cd /var/www/html/skeleton && php bin/phpunit <args>"
docker compose exec -T apache_airbnb sh -lc "cd /var/www/html/skeleton && vendor/bin/phpunit <args>"
# Outil qualité détecté
docker compose exec -T apache_airbnb sh -lc "cd /var/www/html/skeleton && vendor/bin/<outil> <args>"
```

Choisir une seule variante PHPUnit après vérification de son existence. Dans l'état observé du dépôt, Doctrine, Twig, PHPUnit, PHPStan et PHP-CS-Fixer ne sont pas installés : leurs validations sont `NOT_APPLICABLE` tant que les composants/configurations restent absents.

| Changement | Contrôles minimaux |
|---|---|
| PHP/config services | `<prefix> php bin/console lint:container` + tests ciblés |
| Twig | `<prefix> php bin/console lint:twig templates/` + test/rendu ciblé |
| Doctrine | `<prefix> php bin/console doctrine:schema:validate` + revue migration + tests DB |
| Route/contrôleur | `<prefix> php bin/console debug:router` ciblé si utile + test fonctionnel |
| Sécurité | tests authentifié/non authentifié/non propriétaire/CSRF |
| Suite large | `<prefix> php bin/phpunit` si présent |

## Séquence d'exécution

1. `docker compose config --quiet` pour une modification Compose.
2. `docker compose ps` pour vérifier que `apache_airbnb` et, si nécessaire, `mariadb_airbnb` sont disponibles.
3. Vérifier l'existence de l'outil dans `composer.json`, `vendor/bin/` ou `bin/console`.
4. Lancer le test le plus ciblé, puis élargir selon le risque.
5. Si le service est arrêté et que la validation le nécessite : `docker compose up -d`, sans reconstruire sauf modification d'image/Dockerfile.

Exécuter PHPStan et PHP-CS-Fixer uniquement s'ils sont configurés. Ne jamais installer un outil implicitement, ne jamais utiliser `down -v`, ne jamais effacer la base persistante pour faire passer un test. Isoler et signaler tout échec préexistant.

## Validation Evidence

Automatisée :

```text
STATUS: VALIDATED | FAILED | NOT_APPLICABLE | UNABLE_TO_VALIDATE
COMMAND: commande exacte ou `NONE`
EXIT_CODE: entier ou `N/A`
RESULT: résumé quantifié
SCOPE: fichiers/comportement couvert
```

Manuelle/structurelle :

```text
STATUS: VALIDATED | FAILED | NOT_APPLICABLE | UNABLE_TO_VALIDATE
CHECK: contrôle effectué
RESULT: résultat
EVIDENCE: observation vérifiable
FILES: fichiers inspectés
```

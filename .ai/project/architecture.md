# Architecture observée

- Monorepo Docker Compose; application Symfony sous `www/skeleton`.
- Service applicatif `apache_airbnb`, code monté `./www:/var/www/html`, racine CLI `/var/www/html/skeleton`.
- Service DB `mariadb_airbnb` (MariaDB 11.3), volume nommé `mysql`.
- Squelette Symfony 8.1 / PHP >= 8.4 minimal : FrameworkBundle/Console/Dotenv, sans Doctrine, Twig ni PHPUnit actuellement déclarés.
- Toute commande applicative passe par `.ai/standards/validation.md`.

Mettre à jour uniquement lorsqu'une décision durable change ces faits.

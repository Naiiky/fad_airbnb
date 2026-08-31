# Phase 11 - Administration

## Gate de Phase

- [x] Role `ADMIN` disponible.
- [x] `/admin` interdit aux `USER`.
- [ ] Tests securite admin passent. `UNABLE_TO_VALIDATE`: PHPUnit fonctionnel bloque sur `SQLSTATE[HY000] [1044] Access denied for user 'admin'@'%' to database 'airbnb_test'`.

## TASK 11.1 - Securisation /admin

- [x] Ajouter `ROLE_ADMIN`.
- [x] Configurer `access_control`.
- [x] Tester acces `USER` refuse.
- [x] Tester acces admin autorise.

## TASK 11.2 - Utilisateurs

- [x] Creer liste utilisateurs.
- [x] Ajouter recherche.
- [x] Afficher statut.
- [x] Ajouter pagination.
- [x] Ajouter tests.

## TASK 11.3 - Suspension

- [x] Creer action POST.
- [x] Ajouter CSRF.
- [x] Transition `ACTIVE` vers `SUSPENDED`.
- [x] Bloquer login utilisateur suspendu.
- [x] Ajouter tests.

## TASK 11.4 - Logements

- [x] Creer liste globale.
- [x] Ajouter filtres.
- [x] Afficher statut.
- [x] Afficher proprietaire.
- [x] Ajouter tests.

## TASK 11.5 - Moderation Property

- [x] Ajouter action masquer.
- [x] Ajouter CSRF.
- [x] Restreindre admin.
- [x] Ajouter tests.

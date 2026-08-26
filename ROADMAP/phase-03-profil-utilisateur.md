# Phase 03 - Profil Utilisateur

## Gate de Phase

- [x] Authentification operationnelle.
- [x] Acces profil reserve a l'utilisateur connecte.
- [ ] Tests profil passent.

## TASK 3.1 - Consultation du Compte

- [x] Creer route `/account`.
- [x] Creer controller.
- [x] Afficher donnees utilisateur.
- [x] Afficher langues.
- [x] Afficher statut.
- [x] Afficher avatar.
- [x] Ajouter navigation compte.
- [x] Ajouter tests d'acces.

## TASK 3.2 - Modification du Profil

- [x] Creer `ProfileFormType`.
- [x] Champs `firstname` / `lastname`.
- [x] Champ telephone.
- [x] Champ avatar si retenu.
- [x] Champ bio.
- [x] Champ adresse.
- [x] Champ ville.
- [x] Champ CP.
- [x] Champ pays.
- [x] Gestion langues.
- [x] Validations serveur.
- [x] Securite acces utilisateur.
- [x] Tests fonctionnels.

## Validation

- [x] `npm run build`
- [x] `php -l src/Controller/AccountController.php`
- [x] `php -l src/Form/ProfileFormType.php`
- [x] `php -l src/Entity/User.php`
- [x] `php -l tests/AccountProfileTest.php`
- [x] `php bin/console lint:twig templates`
- [x] `php bin/console lint:container`
- [x] `php bin/console debug:router app_account_show`
- [x] `php bin/console debug:router app_account_edit`
- [x] `php bin/console doctrine:schema:validate --skip-sync`
- [ ] `php bin/phpunit tests/AccountProfileTest.php` bloque sur `SQLSTATE[HY000] [1044] Access denied for user 'admin'@'%' to database 'airbnb_test'`.

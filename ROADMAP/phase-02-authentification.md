# Phase 02 - Authentification

## Gate de Phase

- [ ] `MPD_COMPLIANCE = APPROVED`.
- [ ] Tests fonctionnels auth passent. `UNABLE_TO_VALIDATE`: la base `airbnb_test` existe/est attendue par PHPUnit mais l'utilisateur DB applicatif n'a pas les droits (`SQLSTATE[1044]`). Les tests fonctionnels ont été ajoutés.
- [ ] Revue securite auth approuvee.

## TASK 2.1 - Inscription

- [x] Creer `RegistrationFormType`.
- [x] Champ `firstname`.
- [x] Champ `lastname`.
- [x] Champ birth date.
- [x] Champ email.
- [x] Champ password.
- [x] Champ confirmation password.
- [x] Consentement conditions.
- [x] Validations serveur.
- [x] Validation email unique.
- [x] Hash via `PasswordHasher`.
- [x] Creer `RegistrationController`.
- [x] Attribuer `ROLE_USER`.
- [x] Assigner statut `ACTIVE`.
- [x] Assigner statut age initial.
- [x] Definir `emailVerified = true`.
- [x] Enregistrer `termAcceptedAt`.
- [x] Creer template Twig.
- [x] Gerer erreurs.
- [x] Gerer flash.
- [x] Gerer redirection.
- [x] Ajouter tests fonctionnels.

## TASK 2.2 - Controle Majorite

- [x] Creer service dedie.
- [x] Calculer depuis `birthDate`.
- [x] Ne jamais stocker l'age.
- [x] Gerer le jour exact des 18 ans.
- [x] Ajouter tests unitaires.
- [x] Integrer aux actions sensibles.

## TASK 2.3 - Connexion

- [x] Configurer Security.
- [x] Creer login form.
- [x] Ajouter CSRF.
- [x] Gerer session.
- [x] Bloquer utilisateurs `SUSPENDED`.
- [x] Gerer erreurs.
- [x] Gerer redirection.
- [x] Creer template.
- [x] Ajouter tests.

## TASK 2.4 - Deconnexion

- [x] Configurer firewall logout.
- [x] Ajouter action navbar.
- [x] Gerer redirection.
- [x] Tester destruction session.

## Validation

- [x] Figma source of truth utilisee: frames `1:784` (`Havre — Inscription`) et `1:953` (`Havre — Connexion`), assets recuperes dans `assets/images/auth/`.
- [x] `php -l` sur les fichiers PHP auth ajoutes.
- [x] `php bin/console lint:twig templates`.
- [x] `php bin/console lint:container`.
- [x] `php bin/console lint:container --env=test`.
- [x] `php bin/console debug:router` confirme `app_home`, `app_register`, `app_login`, `app_logout`.
- [x] `php bin/console doctrine:schema:validate`.
- [x] `php bin/console debug:asset-map` confirme `styles/app.css`, `images/auth/register-editorial.png`, `images/auth/login-editorial.png`.
- [x] `php bin/phpunit tests/AgeMajorityCheckerTest.php`.
- [ ] `php bin/phpunit tests/AgeMajorityCheckerTest.php tests/AuthenticationFlowTest.php`: bloque sur `airbnb_test` non accessible a l'utilisateur DB applicatif.
- [ ] Controle HTTP navigateur local `/connexion` et `/inscription`: bloque sur le conteneur Apache expose, deja observe `unhealthy`.

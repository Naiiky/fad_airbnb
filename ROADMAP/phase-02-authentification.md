# Phase 02 - Authentification

## Gate de Phase

- [ ] `MPD_COMPLIANCE = APPROVED`.
- [ ] Tests fonctionnels auth passent.
- [ ] Revue securite auth approuvee.

## TASK 2.1 - Inscription

- [ ] Creer `RegistrationFormType`.
- [ ] Champ `firstname`.
- [ ] Champ `lastname`.
- [ ] Champ birth date.
- [ ] Champ email.
- [ ] Champ password.
- [ ] Champ confirmation password.
- [ ] Consentement conditions.
- [ ] Validations serveur.
- [ ] Validation email unique.
- [ ] Hash via `PasswordHasher`.
- [ ] Creer `RegistrationController`.
- [ ] Attribuer `ROLE_USER`.
- [ ] Assigner statut `ACTIVE`.
- [ ] Assigner statut age initial.
- [ ] Definir `emailVerified = false`.
- [ ] Enregistrer `termAcceptedAt`.
- [ ] Creer template Twig.
- [ ] Gerer erreurs.
- [ ] Gerer flash.
- [ ] Gerer redirection.
- [ ] Ajouter tests fonctionnels.

## TASK 2.2 - Controle Majorite

- [ ] Creer service dedie.
- [ ] Calculer depuis `birthDate`.
- [ ] Ne jamais stocker l'age.
- [ ] Gerer le jour exact des 18 ans.
- [ ] Ajouter tests unitaires.
- [ ] Integrer aux actions sensibles.

## TASK 2.3 - Connexion

- [ ] Configurer Security.
- [ ] Creer login form.
- [ ] Ajouter CSRF.
- [ ] Gerer session.
- [ ] Bloquer utilisateurs `SUSPENDED`.
- [ ] Gerer erreurs.
- [ ] Gerer redirection.
- [ ] Creer template.
- [ ] Ajouter tests.

## TASK 2.4 - Deconnexion

- [ ] Configurer firewall logout.
- [ ] Ajouter action navbar.
- [ ] Gerer redirection.
- [ ] Tester destruction session.


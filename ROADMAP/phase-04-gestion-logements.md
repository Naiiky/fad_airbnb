# Phase 04 - Gestion des Logements

## Gate de Phase

- [ ] Authentification operationnelle.
- [ ] Controle majorite disponible.
- [ ] Voters/access control valides.
- [ ] Tests cloisonnement proprietaire passent.

## TASK 4.1 - Creation d'un Logement

- [ ] Creer `PropertyFormType`.
- [ ] Informations generales.
- [ ] Capacite.
- [ ] Chambres.
- [ ] Salles de bain.
- [ ] Lits.
- [ ] Surface.
- [ ] Adresse.
- [ ] Pays.
- [ ] Categorie.
- [ ] Equipements.
- [ ] Animaux.
- [ ] Prix.
- [ ] Injecter proprietaire cote serveur.
- [ ] Definir statut `DRAFT`.
- [ ] Verifier majorite.
- [ ] Ajouter validations.
- [ ] Creer template.
- [ ] Ajouter tests.

## TASK 4.2 - Liste de Mes Logements

- [ ] Creer route `/host/properties`.
- [ ] Query repository par owner.
- [ ] Creer/reutiliser `PropertyCard`.
- [ ] Afficher statut.
- [ ] Afficher actions.
- [ ] Gerer empty state.
- [ ] Ajouter tests de cloisonnement.

## TASK 4.3 - Modification

- [ ] Creer route edit.
- [ ] Creer/adapter `PropertyVoter`.
- [ ] Reutiliser `PropertyFormType`.
- [ ] Autoriser owner/admin.
- [ ] Refuser utilisateur etranger avec 403.
- [ ] Ajouter tests.

## TASK 4.4 - Publication

- [ ] Creer action POST.
- [ ] Definir criteres de completude.
- [ ] Verifier `PropertyVoter`.
- [ ] Verifier majorite.
- [ ] Transition `DRAFT` vers `PUBLISHED`.
- [ ] Definir `publishedAt`.
- [ ] Ajouter CSRF.
- [ ] Ajouter tests.

## TASK 4.5 - Masquage

- [ ] Creer action POST.
- [ ] Ajouter CSRF.
- [ ] Autoriser owner/admin.
- [ ] Transition vers `HIDDEN`.
- [ ] Ajouter confirmation.
- [ ] Ajouter tests.


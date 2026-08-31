# Phase 13 - Favoris

## Gate de Phase

- [x] `FavoriteProperty` existe et respecte le MPD.
- [x] Catalogue et fiche logement operationnels.
- [ ] Tests CSRF et doublons passent. `UNABLE_TO_VALIDATE`: PHPUnit fonctionnel bloque sur `SQLSTATE[HY000] [1044] Access denied for user 'admin'@'%' to database 'airbnb_test'`.

## TASK 13.1 - Ajouter aux Favoris

- [x] Exiger authentification.
- [x] Creer action POST.
- [x] Ajouter CSRF.
- [x] Eviter doublons.
- [x] Creer lien `User` / `Property`.
- [x] Ajouter UI.
- [x] Ajouter tests.

## TASK 13.2 - Retirer des Favoris

- [x] Verifier owner de la relation.
- [x] Creer action POST.
- [x] Ajouter CSRF.
- [x] Ajouter tests.

## TASK 13.3 - Mes Favoris

- [x] Creer liste favoris.
- [x] Reutiliser `PropertyCard`.
- [x] Exclure proprietes non consultables.
- [x] Gerer etat vide.
- [x] Ajouter tests.

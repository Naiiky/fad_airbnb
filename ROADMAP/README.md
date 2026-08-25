# Roadmap Todo Lists

Index des checklists issues de `roadmap.md`.

## Regles d'Utilisation

- Cocher une sous-tache uniquement apres implementation et validation adaptee.
- Cocher une tache principale uniquement lorsque toutes ses sous-taches, validations et gates sont termines.
- Ne pas ajouter de champ ou relation hors MPD sans arbitrage explicite.
- Toutes les commandes Symfony/PHP doivent passer par Docker:
  - `docker compose exec apache_app_airbnb php bin/console ...`
  - `docker compose exec apache_app_airbnb php bin/phpunit`

## Phases

- [Phase 01 - Modele de donnees Doctrine](phase-01-modele-donnees-doctrine.md)
- [Phase 02 - Authentification](phase-02-authentification.md)
- [Phase 03 - Profil utilisateur](phase-03-profil-utilisateur.md)
- [Phase 04 - Gestion des logements](phase-04-gestion-logements.md)
- [Phase 05 - Images](phase-05-images.md)
- [Phase 06 - Catalogue public](phase-06-catalogue-public.md)
- [Phase 07 - Fiche logement](phase-07-fiche-logement.md)
- [Phase 08 - Reservations](phase-08-reservations.md)
- [Phase 09 - Gestion reservations hote](phase-09-gestion-reservations-hote.md)
- [Phase 10 - Univers utilisateur](phase-10-univers-utilisateur.md)
- [Phase 11 - Administration](phase-11-administration.md)
- [Phase 12 - UX Design Responsive](phase-12-ux-design-responsive.md)
- [Phase 13 - Favoris](phase-13-favoris.md)
- [Phase 14 - Tarification calendrier avances](phase-14-tarification-calendrier.md)
- [Phase 15 - Avis](phase-15-avis.md)
- [Phase 16 - Messagerie](phase-16-messagerie.md)
- [Phase 17 - Email verifie](phase-17-email-verifie.md)
- [Phase 18 - Securite tests finaux](phase-18-securite-tests-finaux.md)
- [Phase 19 - Documentation recette](phase-19-documentation-recette.md)

## Gates Majeurs

- [ ] `MPD_ANALYSIS = APPROVED` avant implementation Phase 1.
- [ ] `MPD_COMPLIANCE = APPROVED` avant Phase 2.
- [ ] Arbitrage `Booking.message` avant `TASK 8.4`.
- [ ] Noyau valide apres Phase 12.


# Phase 08 - Reservations

## Gate de Phase

- [ ] Logements publics disponibles.
- [ ] Arbitrage `Booking.message` effectue avant `TASK 8.4`.
- [ ] Tests prix, capacite et disponibilite passent.

## TASK 8.1 - Calcul du Prix

- [ ] Creer `BookingPriceCalculator`.
- [ ] Calculer nombre de nuits.
- [ ] Utiliser `nightlyPrice`.
- [ ] Ne pas appliquer week-end sauf decision explicite.
- [ ] Calculer `night_subtotal`.
- [ ] Reprendre `cleaning_fee`.
- [ ] Reprendre `deposit`.
- [ ] Calculer `total_amount`.
- [ ] Produire snapshots financiers.
- [ ] Refuser tout montant venant du navigateur.
- [ ] Ajouter tests unitaires.

## TASK 8.2 - Verification Capacite

- [ ] Calculer `adultCount + childrenCount`.
- [ ] Comparer a `maxGuest`.
- [ ] Tester limite exacte.
- [ ] Tester depassement.

## TASK 8.3 - Disponibilite

- [ ] Creer methode repository.
- [ ] Utiliser formule `[A,B[`.
- [ ] Considerer bookings `ACCEPTED`.
- [ ] Autoriser dates adjacentes.
- [ ] Tester inclusion.
- [ ] Tester chevauchement partiel.
- [ ] Tester egalite.

## TASK 8.4 - Demande de Reservation

- [ ] Arbitrer le besoin de message facultatif hors `Booking.message`.
- [ ] Creer `BookingFormType`.
- [ ] Champs dates.
- [ ] Champ adultes.
- [ ] Champ enfants.
- [ ] Validation serveur.
- [ ] Verifier capacite.
- [ ] Verifier disponibilite.
- [ ] Calculer prix serveur.
- [ ] Definir status `PENDING`.
- [ ] Associer current user.
- [ ] Sauvegarder snapshot.
- [ ] Gerer flash.
- [ ] Ajouter tests.

## Decision Ouverte - Message de Demande

- [ ] Confirmer que le MPD reste source de verite.
- [ ] Ne pas ajouter silencieusement `Booking.message`.
- [ ] Choisir suppression de l'exigence ou modelisation ailleurs.


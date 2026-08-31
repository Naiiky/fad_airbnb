# Phase 09 - Gestion des Reservations Hote

## Gate de Phase

- [x] Demande de reservation operationnelle.
- [x] `BookingVoter` ou controle equivalent disponible.
- [ ] Tests concurrence/logique critiques passent. `UNABLE_TO_VALIDATE`: PHPUnit fonctionnel bloque sur `SQLSTATE[HY000] [1044] Access denied for user 'admin'@'%' to database 'airbnb_test'`.

## TASK 9.1 - Demandes Recues

- [x] Creer route `/host/bookings`.
- [x] Filtrer `Booking.property.user = CurrentUser`.
- [x] Ajouter filtres.
- [x] Ajouter badges.
- [x] Gerer empty state.
- [x] Ajouter tests.

## TASK 9.2 - Acceptation

- [x] Creer/adapter `BookingVoter`.
- [x] Creer action POST.
- [x] Ajouter CSRF.
- [x] Autoriser `PENDING` uniquement.
- [x] Utiliser transaction.
- [x] Revalider chevauchement.
- [x] Transition vers `ACCEPTED`.
- [x] Ajouter tests concurrents/logiques.

## TASK 9.3 - Refus

- [x] Verifier owner.
- [x] Autoriser `PENDING` uniquement.
- [x] Transition vers `REJECTED`.
- [x] Ajouter CSRF.
- [x] Ajouter tests.

## TASK 9.4 - Annulation

- [x] Definir qui peut annuler.
- [x] Definir jusqu'a quand.
- [x] Definir etats annulables.
- [x] Definir si raison obligatoire.
- [x] Mapper usage `cancellation_reason`.
- [x] Mapper usage `cancellation_date`.
- [x] Transition vers `CANCELLED`.
- [x] Ajouter tests.

# Phase 09 - Gestion des Reservations Hote

## Gate de Phase

- [ ] Demande de reservation operationnelle.
- [ ] `BookingVoter` ou controle equivalent disponible.
- [ ] Tests concurrence/logique critiques passent.

## TASK 9.1 - Demandes Recues

- [ ] Creer route `/host/bookings`.
- [ ] Filtrer `Booking.property.user = CurrentUser`.
- [ ] Ajouter filtres.
- [ ] Ajouter badges.
- [ ] Gerer empty state.
- [ ] Ajouter tests.

## TASK 9.2 - Acceptation

- [ ] Creer/adapter `BookingVoter`.
- [ ] Creer action POST.
- [ ] Ajouter CSRF.
- [ ] Autoriser `PENDING` uniquement.
- [ ] Utiliser transaction.
- [ ] Revalider chevauchement.
- [ ] Transition vers `ACCEPTED`.
- [ ] Ajouter tests concurrents/logiques.

## TASK 9.3 - Refus

- [ ] Verifier owner.
- [ ] Autoriser `PENDING` uniquement.
- [ ] Transition vers `REJECTED`.
- [ ] Ajouter CSRF.
- [ ] Ajouter tests.

## TASK 9.4 - Annulation

- [ ] Definir qui peut annuler.
- [ ] Definir jusqu'a quand.
- [ ] Definir etats annulables.
- [ ] Definir si raison obligatoire.
- [ ] Mapper usage `cancellation_reason`.
- [ ] Mapper usage `cancellation_date`.
- [ ] Transition vers `CANCELLED`.
- [ ] Ajouter tests.


# Phase 16 - Messagerie

## Gate de Phase

- [ ] Entites `Conversation` et `Message` conformes MPD.
- [ ] Acces conversations securise.
- [ ] Tests IDOR messagerie passent.

## TASK 16.1 - Creer/Ouvrir une Conversation

- [ ] Gerer relation `User`.
- [ ] Gerer relation `Property`.
- [ ] Verifier acces.
- [ ] Eviter conversations incoherentes.
- [ ] Gerer `createdAt`.
- [ ] Ajouter tests.

## TASK 16.2 - Liste Conversations

- [ ] Filtrer utilisateur courant.
- [ ] Afficher propriete.
- [ ] Afficher dernier message.
- [ ] Afficher `lastMessageAt`.
- [ ] Ajouter tests.

## TASK 16.3 - Envoyer Message

- [ ] Verifier conversation autorisee.
- [ ] Assigner `sender = current user`.
- [ ] Valider `content`.
- [ ] Mettre a jour `lastMessageAt`.
- [ ] Ajouter tests.

## TASK 16.4 - Lecture

- [ ] Gerer `readAt`.
- [ ] Restreindre au destinataire pertinent.
- [ ] Calculer compteur eventuel.
- [ ] Ajouter tests.


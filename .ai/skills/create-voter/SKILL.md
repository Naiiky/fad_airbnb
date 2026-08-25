---
name: create-voter
description: Implémenter une autorisation objet Symfony avec Voter et tests anti-IDOR.
---

# Créer un Voter

1. Lire `.ai/rules/security.md` et rechercher rôles, ownership et Voters existants.
2. Définir attributs, sujet, préconditions et politique par défaut; refuser l'accès ambigu.
3. Implémenter le Voter avec identité et relation métier côté serveur, sans donnée client de confiance.
4. Appeler l'autorisation avant lecture sensible ou mutation; centraliser la règle plutôt que la dupliquer.
5. Tester propriétaire autorisé, autre utilisateur refusé, anonyme et rôle privilégié éventuel.

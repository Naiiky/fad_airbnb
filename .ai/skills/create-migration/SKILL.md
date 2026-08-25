---
name: create-migration
description: Générer, relire et valider une migration Doctrine sûre après une évolution de mapping.
---

# Créer une migration Doctrine

1. Lire le mapping final et l'historique des migrations; ne jamais écrire une migration depuis une hypothèse.
2. Générer le diff avec l'outil Doctrine disponible, puis relire chaque instruction SQL.
3. Vérifier données existantes, valeurs par défaut, nullabilité, indexes, clés étrangères, volume et verrouillage.
4. Séparer en plusieurs étapes un changement incompatible (backfill puis contrainte) si nécessaire.
5. Vérifier `up` et stratégie de retour; ne pas prétendre qu'un rollback destructif est sûr.
6. Exécuter schema validate et un test de migration sur base jetable lorsque l'environnement le permet.

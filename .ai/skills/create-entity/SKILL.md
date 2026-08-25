---
name: create-entity
description: Créer ou faire évoluer une entité Doctrine, ses relations et contraintes; ne couvre pas à elle seule la migration.
---

# Créer ou modifier une entité

1. Rechercher entités, conventions de mapping et usages existants; lire `.ai/rules/symfony.md`.
2. Établir identité, types, nullabilité, cardinalités, ownership, cascades, contraintes et indexes.
3. Préserver les invariants dans une API d'entité explicite; éviter les setters incohérents.
4. Mettre à jour les deux côtés d'une relation lorsque nécessaire; éviter eager loading par défaut.
5. Confier la migration au skill `create-migration`; ajouter tests d'invariants et d'intégration utiles.
6. Valider mapping et schéma sans modifier des données hors périmètre.

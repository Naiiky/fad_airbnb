---
name: create-unit-test
description: Créer un test unitaire PHP pour une logique pure ou un service isolable; éviter pour les flux nécessitant Symfony/Doctrine réels.
---

# Créer un test unitaire

1. Choisir ce niveau uniquement si le comportement est prouvable sans kernel, base ni HTTP.
2. Suivre les conventions PHPUnit existantes et nommer les tests par comportement.
3. Couvrir nominal, limites et erreurs utiles; limiter les mocks aux frontières réelles.
4. Ne pas tester getters triviaux, framework ou détails d'implémentation.
5. Exécuter le fichier ciblé et signaler toute dépendance empêchant le test.

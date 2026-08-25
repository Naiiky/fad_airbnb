---
name: create-functional-test
description: Créer un test fonctionnel Symfony pour routes, formulaires, sécurité et flux intégrés.
---

# Créer un test fonctionnel

1. Rechercher la base de tests, factories/fixtures et helpers existants; ne pas inventer un second harness.
2. Tester le comportement observable et les effets persistés, pas les détails internes.
3. Couvrir happy path, validation, ressource absente et autorisation selon le risque.
4. Pour une mutation navigateur, inclure CSRF; pour ownership, tester au moins propriétaire et non-propriétaire.
5. Isoler les données et rendre le test déterministe; éviter délais et dépendances réseau.
6. Exécuter le test ciblé puis la suite pertinente et rapporter les commandes exactes.

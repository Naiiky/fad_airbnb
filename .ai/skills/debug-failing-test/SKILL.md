---
name: debug-failing-test
description: Diagnostiquer un test Symfony/PHP en échec, isoler sa cause et éviter les retries ou corrections aveugles.
---

# Déboguer un test rouge

1. Exécuter la commande la plus ciblée via `.ai/standards/validation.md` et conserver commande, exit code et message utile.
2. Reproduire une seconde fois seulement si la flakiness est plausible; ne pas boucler.
3. Classer la cause : produit, test, fixture, environnement, dépendance ou intermittence.
4. Tracer le chemin minimal et comparer avec le comportement attendu/les tests voisins.
5. Proposer la plus petite correction prouvée; ne jamais affaiblir une assertion uniquement pour obtenir vert.
6. Revalider ciblé puis périmètre adjacent et produire Validation Evidence.

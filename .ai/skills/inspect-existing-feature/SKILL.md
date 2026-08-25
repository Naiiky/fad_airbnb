---
name: inspect-existing-feature
description: Inspecter un comportement existant, tracer son flux et identifier les patterns réutilisables avant une feature, un bugfix ou un refactor.
---

# Inspecter une fonctionnalité existante

1. Partir du point d'entrée observable : route, commande, template ou test.
2. Tracer appelants, contrôleur, service, repository, entités, rendu et effets de bord avec fichiers/symboles.
3. Rechercher implémentations, composants et tests analogues avant de proposer une création.
4. Distinguer comportement prouvé, convention répétée et hypothèse.
5. Retourner flux, patterns réutilisables, impacts, inconnues et fichiers pertinents; rester read-only sauf mandat distinct.

---
name: analyze-access-control
description: Cartographier et tester une frontière d'authentification ou d'autorisation Symfony, notamment ownership et risques IDOR.
---

# Analyser un contrôle d'accès

1. Tracer l'entrée jusqu'à la ressource et relever firewall, access control, rôles, Voters et contrôles locaux.
2. Définir sujet, action, propriétaire, rôles privilégiés et politique de refus.
3. Vérifier que la décision est serveur, avant lecture sensible/mutation, sans confiance dans l'identifiant client.
4. Construire la matrice : anonyme, utilisateur légitime, autre utilisateur, rôle privilégié, ressource absente.
5. Retourner findings traçables et tests requis; référencer `.ai/rules/security.md` et la validation Docker centrale.

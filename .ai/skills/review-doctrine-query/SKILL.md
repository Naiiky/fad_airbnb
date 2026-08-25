---
name: review-doctrine-query
description: Auditer une requête Doctrine pour correction, sécurité, N+1, indexes et coût avant ou après modification.
---

# Revoir une requête Doctrine

1. Tracer les appelants, cardinalités, volume attendu, pagination et données retournées.
2. Vérifier paramètres liés, filtres d'autorisation, joins, hydratation, N+1 et chargement inutile.
3. Corréler prédicats/tri avec les indexes et contraintes réels; ne proposer un index qu'avec un accès justifié.
4. Vérifier résultat vide, doublons, nulls et stabilité de l'ordre.
5. Fournir findings avec preuve; si mandaté writer, appliquer le plus petit changement et tester résultat + nombre de requêtes si possible.

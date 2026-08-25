# Definition of Ready

Avant implémentation, l'Orchestrateur doit pouvoir énoncer :

- objectif et résultat observable;
- périmètre inclus/exclu;
- critères d'acceptation et cas limites principaux;
- fichiers/couches probablement concernés;
- dépendances, propriétaires et ordre;
- risques fonctionnels, données, sécurité et régression;
- `PRIMARY_TYPE`, `IMPACT_FLAGS`, `COMPLEXITY`, `RISK` et profil;
- pour plusieurs work units : DAG acyclique avec dépendances, modes et ownership; pour MICRO : unité implicite acceptable;
- contrats d'interface uniquement aux frontières partagées, stabilisés ou tâches sérialisées;
- validation et reviews prévues.

Déduire ces éléments du dépôt et des conventions. `BLOCKED` n'est permis qu'après inspection et lorsqu'une décision produit importante, non réversible ou risquée reste impossible à inférer.

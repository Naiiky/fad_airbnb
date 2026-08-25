---
name: analyze-doctrine-impact
description: Analyser l'impact Doctrine d'une évolution sur entités, relations, requêtes, données existantes et stratégie de migration.
---

# Analyser un impact Doctrine

1. Vérifier que Doctrine est réellement installé puis lire mappings, migrations, repositories et usages concernés.
2. Cartographier cardinalités, ownership, nullabilité, unicité, cascades, indexes et volumes connus.
3. Évaluer données existantes, compatibilité ascendante, backfill, verrouillage, rollback et déploiement par étapes.
4. Rechercher N+1, doublons, courses et besoin transactionnel.
5. Produire options, recommandation, risques, plan de migration et validations; ne pas générer la migration sans mandat writer.

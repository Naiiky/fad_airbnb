# Veto et boucle de correction

QA exerce un veto fonctionnel/tests, Security un veto sécurité, Code Review un veto correction/maintenabilité majeure.

Pour chaque finding obligatoire, l'Orchestrateur conserve son `FINDING_ID` stable et `CORRECTION_COUNTERS[FINDING_ID]` :

1. Router au writer propriétaire avec preuve, comportement attendu et validation à relancer.
2. Le writer corrige uniquement ce finding et fournit son résultat structuré.
3. Le reviewer d'origine revalide indépendamment.
4. Le writer peut retourner `DISPUTED` avec preuve; appliquer alors l'arbitrage de `findings.md`.
5. Arrêter dès `RESOLVED` puis revalidation `APPROVED`; maximum trois cycles automatiques pour le même ID.
6. Après le troisième échec, le Team Lead analyse : REQUIREMENTS, PLAN, ARCHITECTURE, IMPLEMENTATION, TEST, REVIEW, ENVIRONMENT, DEPENDENCY ou TOOLING.
7. Si une décision métier irréductible subsiste, retourner `BLOCKED` avec une seule question précise; sinon replanner une fois sans répéter à l'identique.

Ne jamais contourner un veto en supprimant ou affaiblissant un test sans preuve que le test est incorrect.

Après chaque correction, relancer seulement les validations liées au finding, conserver son ID et mettre à jour son état. Ne pas recommencer l'orchestration complète.

Classifier tout échec avant routage : `IMPLEMENTATION_FAILURE`, `VALIDATION_FAILURE`, `ENVIRONMENT_FAILURE`, `TOOLING_FAILURE` ou `DEPENDENCY_FAILURE`. Router au writer uniquement un échec d'implémentation prouvé; Docker/MySQL indisponible relève de l'environnement, pas automatiquement du PHP.

# Contrat de résultat inter-agents

Chaque agent retourne exactement ces sections, brièvement :

```text
STATUS: READY | IN_PROGRESS | BLOCKED | CHANGES_REQUIRED | APPROVED | FAILED
SUMMARY: résultat en quelques lignes
FINDINGS:
- finding structuré selon findings.md ou `NONE`
DECISIONS: décisions prises ou recommandées
ACTIONS: fichiers modifiés, commandes exécutées, prochaine action
FILES_READ: liste ciblée
FILES_CHANGED: liste ou `NONE`
VALIDATION: VALIDATED | FAILED | NOT_APPLICABLE | UNABLE_TO_VALIDATE
EVIDENCE: commandes structurées ou preuves manuelles
BLOCKERS: obstacle, information manquante ou `NONE`
OUT_OF_SCOPE_FINDINGS: liste ou `NONE`
```

En cas d'échec, ajouter `FAILURE_TYPE: IMPLEMENTATION_FAILURE | VALIDATION_FAILURE | ENVIRONMENT_FAILURE | TOOLING_FAILURE | DEPENDENCY_FAILURE`.

Un finding suit `findings.md`. `BLOCKER`, `CRITICAL`, `MAJOR` exigent correction/arbitrage. `MINOR` et `SUGGESTION` n'ouvrent pas automatiquement une boucle.

Un reviewer retourne `APPROVED` uniquement s'il n'existe aucun finding obligatoire. `CHANGES_REQUIRED` doit identifier le writer responsable et un critère de sortie vérifiable.

`DISPUTED` est un statut de finding, jamais un `STATUS` de résultat : le writer retourne `STATUS: CHANGES_REQUIRED` et inclut le finding concerné avec `STATUS: DISPUTED` et sa preuve.

`EVIDENCE` suit les schémas automatisé/manuel de `../standards/validation.md`. L'Orchestrator agrège chaque entrée dans `VALIDATION_EVIDENCE` avec `VALIDATED | NOT_APPLICABLE | UNABLE_TO_VALIDATE`; un résumé libre ne remplace jamais les champs de preuve.

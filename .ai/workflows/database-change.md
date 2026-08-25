# Workflow DATABASE

- **DEFAULT_PROFILE** — `STANDARD`; `FULL` pour migration significative, données sensibles ou opération irréversible.
- **ENTRY_CONDITIONS** — `PRIMARY_TYPE=DATABASE` ou flags DATABASE/DOCTRINE/MIGRATION.
- **DEFAULT_AGENTS** — Database Expert, Symfony Developer, QA.
- **CONDITIONAL_AGENTS** — Analyst si règle métier ambiguë; Lead si plusieurs étapes; Security si données sensibles; Architect si modèle structurel; Code Review pour COMPLEX+.
- **EXECUTION_PHASES** — Inspection mappings/données -> recommandation DB -> plan/contrat -> implémentation Doctrine/migration -> validation -> reviews.
- **DEPENDENCIES** — Migration après mapping final et stratégie données; suppression/anonymisation après décision métier explicite.
- **PARALLELIZATION_POINTS** — Analyse DB || analyse sécurité read-only; entité et migration restent sous un writer coordonné.
- **REQUIRED_REVIEWS** — QA intégrité; DB relecture; Security si privacy; Code Review selon profil.
- **VALIDATIONS** — Schéma/migration/tests uniquement si outils présents, via standard central.
- **EXIT_CONDITIONS** — Mapping cohérent, migration relue, données existantes traitées, N+1/contraintes évalués.
- **ESCALATION** — Choix irréversible incompatible non déductible: utilisateur; trois échecs: RCA Lead.

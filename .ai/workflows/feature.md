# Workflow FEATURE

- **DEFAULT_PROFILE** — `STANDARD`; `MICRO` si local/LOW; `FULL` si auth, sécurité, migration/architecture significative ou risque HIGH+.
- **ENTRY_CONDITIONS** — `PRIMARY_TYPE=FEATURE`.
- **DEFAULT_AGENTS** — Writer concerné, QA.
- **CONDITIONAL_AGENTS** — Analyst si exigence ambiguë; Lead si multi-fichiers/couches; DB pour Doctrine; Frontend pour UI; UI/UX pour décision UX; Security pour flags sensibles; Architect pour décision structurelle; Code Review pour COMPLEX+ ou risque MEDIUM+.
- **EXECUTION_PHASES** — Discovery -> requirements utiles -> plan -> contrat/DAG -> writers -> validation -> reviews -> correction éventuelle.
- **DEPENDENCIES** — Implémentation après requirements/plan applicables; QA après intégration.
- **PARALLELIZATION_POINTS** — DB analysis || UI/UX; backend || frontend seulement après contrat `STABLE` et ownership disjoint.
- **REQUIRED_REVIEWS** — QA; Security si flag sécurité/auth/privacy; Code Review selon complexité/risque.
- **VALIDATIONS** — Sélection via `../standards/validation.md`.
- **EXIT_CONDITIONS** — Critères acceptés, findings obligatoires clos, preuves applicables collectées.
- **ESCALATION** — Contrat instable: sérialiser; décision métier irréversible: utilisateur; trois échecs: RCA Lead.

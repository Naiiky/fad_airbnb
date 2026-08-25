# Workflow BUG

- **DEFAULT_PROFILE** — `STANDARD`; `MICRO` si local/LOW; `FULL` si risque HIGH+.
- **ENTRY_CONDITIONS** — `PRIMARY_TYPE=BUG`, y compris bug avec flags sécurité.
- **DEFAULT_AGENTS** — Writer propriétaire.
- **CONDITIONAL_AGENTS** — QA pour comportement, régression, profil `STANDARD|FULL` ou risque sensible; Lead pour cause transverse; Security prioritaire pour auth/autorisation/données; DB pour requête/intégrité; DevOps pour runtime; Code Review selon portée/complexité.
- **EXECUTION_PHASES** — Reproduction/preuve -> cause racine -> test de régression -> correction minimale -> validation -> review.
- **DEPENDENCIES** — Correction après reproduction ou preuve suffisante; review après test.
- **PARALLELIZATION_POINTS** — Reproduction || cartographie read-only; writers sérialisés si même chemin.
- **REQUIRED_REVIEWS** — QA pour `STANDARD|FULL`, sécurité ou régression significative; en `MICRO/LOW`, validation ciblée par le writer si elle prouve entièrement la correction. Security obligatoire pour flags SECURITY/AUTHORIZATION/AUTHENTICATION.
- **VALIDATIONS** — Test de régression obligatoire pour sécurité; preuve ciblée proportionnée pour bug `MICRO`; autres contrôles via standard central.
- **EXIT_CONDITIONS** — Défaut absent, régression prouvée, findings clos.
- **ESCALATION** — Non reproductible: Lead; risque actif: priorité Security; trois échecs: RCA.

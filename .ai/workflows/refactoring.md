# Workflow REFACTOR

- **DEFAULT_PROFILE** — `STANDARD`; `FULL` pour système transverse/VERY_COMPLEX/HIGH.
- **ENTRY_CONDITIONS** — `PRIMARY_TYPE=REFACTOR`, comportement attendu inchangé.
- **DEFAULT_AGENTS** — Lead, writer, QA, Code Reviewer.
- **CONDITIONAL_AGENTS** — Architect uniquement si frontières/dépendances changent; DB si requêtes/mappings; Analyst seulement si comportement incertain.
- **EXECUTION_PHASES** — Inspection approfondie -> caractérisation par tests -> plan -> refactor incrémental -> validation -> review.
- **DEPENDENCIES** — Aucune architecture avant cartographie; changement après tests de caractérisation applicables.
- **PARALLELIZATION_POINTS** — Cartographie || inventaire tests en read-only; writers des mêmes classes sérialisés.
- **REQUIRED_REVIEWS** — QA non-régression + Code Review; spécialistes selon flags.
- **VALIDATIONS** — Tests avant/après et standard central.
- **EXIT_CONDITIONS** — Comportement préservé, objectif de structure mesurable atteint, aucun scope fonctionnel implicite.
- **ESCALATION** — Comportement ambigu ou absence de filet sur zone risquée: Lead puis utilisateur seulement si métier indéductible.

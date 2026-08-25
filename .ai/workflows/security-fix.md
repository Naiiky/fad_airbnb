# Workflow SECURITY

- **DEFAULT_PROFILE** — `FULL`; `STANDARD` seulement si risque démontré LOW/MEDIUM et périmètre local.
- **ENTRY_CONDITIONS** — `PRIMARY_TYPE=SECURITY` ou flags SECURITY/AUTHORIZATION/AUTHENTICATION/PRIVACY.
- **DEFAULT_AGENTS** — Security Reviewer en triage, writer, QA, Security Reviewer final.
- **CONDITIONAL_AGENTS** — Analyst pour règle métier; Lead pour flux transverse; DB/Frontend/DevOps selon surface; Code Review pour risque HIGH+ ou COMPLEX+.
- **EXECUTION_PHASES** — Triage/contenir -> requirements/plan utiles -> correction serveur -> tests de régression -> QA + Security indépendants -> Code Review conditionnelle.
- **DEPENDENCIES** — Correction après menace/preuve; reviewers après intégration; aucun auteur ne revalide son propre travail.
- **PARALLELIZATION_POINTS** — Threat analysis || cartographie read-only; QA || Security après intégration.
- **REQUIRED_REVIEWS** — QA et Security; Code Review seulement pour refactor, logique métier significative, architecture, diff important, changement transversal ou complexité élevée.
- **VALIDATIONS** — Légitime, anonyme, non-propriétaire et entrée hostile pertinente.
- **EXIT_CONDITIONS** — Cause supprimée côté serveur, finding/veto levé, preuves de régression.
- **ESCALATION** — `DISPUTED` vers Lead; vulnérabilité importante démontrée conserve veto Security.

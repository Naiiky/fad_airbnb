# Workflow UI

- **DEFAULT_PROFILE** — `MICRO` pour texte/classe; sinon `STANDARD`.
- **ENTRY_CONDITIONS** — `PRIMARY_TYPE=UI` ou flag UI.
- **DEFAULT_AGENTS** — Frontend Developer.
- **CONDITIONAL_AGENTS** — QA si comportement; UI/UX seulement pour vraie décision UX; Symfony Developer si données/formulaire; Security si mutation/contenu/permission; Code Review si JS/flux complexe.
- **EXECUTION_PHASES** — Inspection ciblée -> contrat de rendu si nécessaire -> implémentation -> validation Twig/interaction -> review conditionnelle.
- **DEPENDENCIES** — Données/route stabilisées avant frontend consommateur.
- **PARALLELIZATION_POINTS** — UI/UX read-only || backend analysis; backend/frontend seulement avec contrat stable.
- **REQUIRED_REVIEWS** — QA léger selon comportement; accessibilité pour composant significatif.
- **VALIDATIONS** — Twig/test ciblé; états vide/erreur/succès, clavier/responsive selon périmètre.
- **EXIT_CONDITIONS** — Rendu cohérent, interaction prouvée, aucun agent disproportionné.
- **ESCALATION** — Contrat de données instable: sérialiser.

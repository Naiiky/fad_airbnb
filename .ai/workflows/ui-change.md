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

## Figma source of truth

When a task provides a Figma frame or node:

1. The Figma design is the visual source of truth.
2. Retrieve the design context through Figma MCP before implementation.
3. Do not infer dimensions, spacing, typography, colors or assets when those values are available from Figma.
4. Reuse existing Twig components and Tailwind conventions when they can reproduce the Figma design faithfully.
5. Do not introduce React, Vue or another frontend framework from generated Figma examples.
6. Translate the design into the project's stack: Symfony 8 + Twig + Tailwind CSS + Stimulus only when interaction requires JavaScript.
7. Reuse Figma assets when available.
8. Preserve responsive behavior represented by the design.
9. Report any conflict between Figma and existing project conventions instead of silently redesigning the interface.

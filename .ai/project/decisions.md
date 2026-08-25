# Décisions durables

- `AGENTS.md` reste le point d'entrée racine; `.ai/` est la source documentaire unique.
- Le Task State reste en contexte par défaut, sans chain-of-thought; matérialisation seulement pour reprise explicite.
- Les skills sous `.ai/skills` sont chargés explicitement par l'Orchestrator et ne sont pas annoncés comme skills `$` auto-découverts.
- La syntaxe Docker est centralisée dans `../standards/validation.md`.

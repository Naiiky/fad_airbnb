# Orchestration du projet

Ce dépôt utilise Codex comme chef d'orchestre d'une équipe Symfony spécialisée. Pour toute demande, agir comme `orchestrator` et lire d'abord `.ai/README.md`, puis uniquement les fichiers qu'il route.

## Démarrage obligatoire

1. Inspecter le dépôt selon `SEARCH -> UNDERSTAND -> REUSE -> MODIFY -> CREATE`.
2. Classifier la tâche et sa complexité avec `.ai/rules/routing.md`.
3. Maintenir un Task State proportionné avec `.ai/templates/task-state.md` et suivre `.ai/protocol/state-machine.md`; pour `MICRO`, garder seulement les champs utiles.
4. Établir la Definition of Ready, choisir le profil et un workflow dans `.ai/workflows/`.
5. Construire un DAG seulement si plusieurs work units le justifient; un writer `MICRO` peut utiliser une unité implicite. Créer un contrat d'interface uniquement pour une frontière partagée.
6. Déléguer seulement les missions utiles définies dans `.ai/agents/`; paralléliser uniquement les work units indépendantes.
7. Imposer `.ai/protocol/result-contract.md`, `.ai/protocol/findings.md` et un seul writer actif par fichier.
8. Valider selon `.ai/standards/definition-of-done.md` et `.ai/standards/validation.md`.

## Règles non négociables

- Respecter `.ai/rules/global.md`, `.ai/rules/symfony.md` et `.ai/rules/security.md`.
- Ne jamais transmettre tout le dépôt à un sous-agent : fournir un context packet ciblé.
- QA, Security Reviewer et Code Reviewer ne valident jamais leur propre travail et disposent d'un veto justifié.
- Corriger automatiquement les constats `BLOCKER`, `CRITICAL` ou `MAJOR`, au plus trois cycles par problème.
- Ne pas modifier hors périmètre ; remonter `OUT_OF_SCOPE_FINDING` sauf blocage ou vulnérabilité critique.
- Ne demander à l'utilisateur que les décisions produit impossibles à déduire et à fort impact.
- Ne jamais déclarer terminé sans preuves de validation, ou sans signaler clairement les validations impossibles.
- L'Orchestrator seul possède l'état global et peut passer la tâche à `DONE`.

Application Symfony : `www/skeleton`. Infrastructure : `docker-compose.yml`, `apache/`, `db/`. Exécuter les commandes applicatives via Docker selon `.ai/standards/validation.md`.

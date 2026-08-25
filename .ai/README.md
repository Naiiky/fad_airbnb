# Système d'orchestration Symfony

Ce dossier est l'unique source de vérité de l'équipe multi-agents. `AGENTS.md` à la racine est seulement le pointeur minimal nécessaire à la découverte automatique par Codex.

## Organisation

- `agents/` : responsabilités, frontières et contrats de rôle.
- `rules/` : contraintes permanentes et routage.
- `workflows/` : séquences adaptables par type de tâche.
- `standards/` : seuils Ready/Done et matrice de validation.
- `protocol/` : échanges, veto et corrections.
- `templates/` : paquets minimaux remis aux sous-agents.
- `skills/` : opérations Symfony réutilisables, chargées par l'Orchestrateur lorsqu'elles correspondent à la tâche.
- `project/` : faits, conventions et décisions durables réellement observés.

## Algorithme de l'orchestrateur

1. Créer en mémoire un Task State adaptatif depuis `templates/task-state.md`; omettre les champs inutiles et ne pas l'écrire dans le dépôt sauf reprise explicite.
2. Suivre la machine d'état de `protocol/state-machine.md`.
3. Classer `PRIMARY_TYPE`, `IMPACT_FLAGS`, `COMPLEXITY`, `RISK` et choisir `MICRO`, `STANDARD` ou `FULL` via `rules/routing.md`.
4. Pour `MICRO`, utiliser le fast path : discovery ciblée -> un writer -> validation ciblée -> Done; aucun artefact vide.
5. Sinon atteindre Ready, choisir le workflow minimal et retirer tout agent sans contribution unique.
6. Faire produire le plan technique par le Team Lead seulement si plusieurs composants/dépendances le justifient.
7. Construire le DAG seulement pour plusieurs work units; ajouter ownership et contrats uniquement aux frontières partagées.
8. Déléguer avec `templates/context-packet.md`; garder analyses et reviews read-only.
9. Collecter le contrat `protocol/result-contract.md`, remplacer les conclusions périmées et synchroniser les dépendances.
10. Router findings, désaccords et corrections avec `protocol/findings.md` et `protocol/correction-loop.md`.
11. Enregistrer les preuves selon `standards/validation.md`, appliquer Done et livrer le rapport final.

Ne jamais confondre rôle, skill, règle, workflow et standard.

## Modèle d'exécution réel

Codex conserve le Task State dans le contexte actif et utilise ses outils de sous-agents pour les work units indépendantes. Les fichiers `.ai/` décrivent le protocole; ils ne prétendent pas fournir un moteur externe. Le Task State n'est matérialisé que si la tâche doit survivre à une session, et jamais avec du raisonnement privé.

## Environnement Docker du projet

- Compose : `docker-compose.yml` à la racine.
- Service PHP/Apache : `apache_airbnb`.
- Service MariaDB : `mariadb_airbnb`.
- Projet Symfony hôte : `www/skeleton`.
- Projet Symfony conteneur : `/var/www/html/skeleton`.
- Le nom effectif du conteneur peut être surchargé par `APACHE_CONTAINER`; utiliser le nom de service Compose.

Toute détection, commande et preuve Docker est centralisée dans `standards/validation.md`. Ne recopier sa syntaxe ni dans les agents, ni dans les workflows, ni dans les skills.

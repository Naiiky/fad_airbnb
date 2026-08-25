# Règles globales

- Priorité : fiabilité, correction, Symfony natif, sécurité, simplicité, autonomie, coût.
- Inspecter avant de créer : rechercher patterns, services, composants, tests et conventions.
- Préférer une modification locale et réversible à une abstraction nouvelle.
- Un seul writer actif par fichier et par phase. Les reviewers restent read-only.
- Paralléliser seulement des tâches sans dépendance ni fichiers communs.
- Ne jamais exposer, journaliser ou committer un secret. Ne pas lire `.env` sans nécessité explicite.
- Préserver les changements utilisateur et ignorer les modifications non liées.
- Toute dépendance de production, migration destructive ou action externe irréversible exige une justification explicite et les autorisations applicables.
- Signaler un problème hors périmètre comme `OUT_OF_SCOPE_FINDING`; ne le corriger que s'il bloque le travail ou s'il est critique.
- Fournir des preuves : commandes, résultats, fichiers concernés et validations non exécutées.
- Avant d'écrire un fichier important, le relire et vérifier qu'il correspond encore aux hypothèses du context packet; si le contexte est périmé, réévaluer sans construire de système de versioning.
- Principe de review : `Review the current repository state, not a stale snapshot.`

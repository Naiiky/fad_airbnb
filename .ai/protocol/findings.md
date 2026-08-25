# Findings traçables et arbitrage

Format obligatoire pour tout finding important :

```text
ID: <SOURCE>-NNN
SOURCE: QA | SECURITY | CODE | ARCH | DB | OTHER
CATEGORY: catégorie stable
SEVERITY: BLOCKER | CRITICAL | MAJOR | MINOR | SUGGESTION
LOCATION: fichier:symbole ou artefact
PROBLEM: défaut observable
EVIDENCE: reproduction, sortie ou règle violée
REQUIRED_OUTCOME: résultat vérifiable attendu sans imposer l'implémentation
OWNER: work unit/agent
STATUS: OPEN | ACCEPTED | IN_PROGRESS | RESOLVED | DISPUTED | DISMISSED
```

L'Orchestrator attribue l'ID, déduplique par cause/localisation et maintient son statut. `BLOCKER`, `CRITICAL`, `MAJOR` exigent correction ou arbitrage. Le reviewer décrit le résultat attendu; le writer choisit l'implémentation conforme au dépôt.

`DISPUTED` reste exceptionnel : preuve incorrecte/périmée, contradiction avec requirements, hors scope, outcome déjà satisfait ou comportement mal interprété. Une préférence d'implémentation ne suffit jamais.

Arbitrage : reviewer finding -> writer place le finding `DISPUTED` avec preuve -> Team Lead décide :

- `UPHELD` : finding -> `ACCEPTED`, sévérité conservée, résultat global `CHANGES_REQUIRED`, correction planifiée; compteur incrémenté à l'exécution;
- `DOWNGRADED` : sévérité remplacée explicitement; si elle reste `MAJOR+`, même chemin que `UPHELD`; sinon finding ouvert non obligatoire sans boucle automatique;
- `DISMISSED` : finding -> `DISMISSED`, aucune correction ni incrément, retrait des blockers.

L'Orchestrator enregistre décision/preuve dans le Task State, actualise DAG et transition. Security conserve son veto pour une vulnérabilité importante démontrée; un désaccord sans preuve ne le lève pas.

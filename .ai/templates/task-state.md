# Task State

Source de vérité opérationnelle actuelle maintenue par l'Orchestrator dans le contexte actif. Stocker les conclusions, jamais conversation, réponses brutes ou chain-of-thought. Remplacer les conclusions périmées au lieu de les accumuler. Matérialiser seulement pour une reprise inter-session explicite.

```yaml
TASK_ID: TASK-YYYYMMDD-NNN
USER_REQUEST: ""
CURRENT_STATE: RECEIVED
PRIMARY_TYPE: null
IMPACT_FLAGS: []
COMPLEXITY: null
RISK: null
EXECUTION_PROFILE: null
REQUIREMENTS: []
ACCEPTANCE_CRITERIA: []
DECISIONS: []
ASSUMPTIONS: []
CONSTRAINTS: []
TASK_GRAPH: []
OWNERSHIP: {}
INTERFACE_CONTRACTS: []
OPEN_FINDINGS: []
VALIDATION_EVIDENCE: []
CORRECTION_COUNTERS: {}
BLOCKERS: []
```

Adaptation : `MICRO` conserve seulement ID, demande, état, classification/profil, critère, scope, validation et blocker éventuel. `STANDARD` ajoute DAG/ownership/findings utiles. `FULL` utilise tous les champs nécessaires. Un champ inutile est omis ou `N/A`; ne jamais produire une structure vide pour la forme.

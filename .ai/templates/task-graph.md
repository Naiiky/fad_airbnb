# Task Graph

Chaque work unit utilise ce contrat :

```yaml
ID: T1
DESCRIPTION: ""
OWNER: agent
MODE: READ_ONLY | WRITER | REVIEWER
DEPENDS_ON: []
RELEVANT_SCOPE: []
STATUS: PENDING | READY | IN_PROGRESS | BLOCKED | COMPLETED | FAILED
INTERFACE_CONTRACT: N/A
VALIDATION: N/A
```

`INTERFACE_CONTRACT` et `VALIDATION` sont omis s'ils n'aident pas l'exécution. Pour MICRO à un writer, garder le graphe implicite. Avant exécution : IDs uniques, aucun cycle, un writer actif par fichier. Distinguer `FILE_OWNERSHIP` (qui modifie) et `BOUNDARY_OWNERSHIP` (qui définit route, DTO, variable Twig, service ou contrat partagé). Deux unités sont parallèles seulement si dépendances satisfaites, writes disjoints et frontière partagée stabilisée.

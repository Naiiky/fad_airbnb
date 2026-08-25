# Interface Contract

Créer ce contrat uniquement lorsque des work units exécutées indépendamment partagent une frontière. L'omettre pour une unité isolée ou un writer unique.

```yaml
CONTRACT_ID: IC-001
PRODUCER: Tn
CONSUMERS: [Tm]
STATUS: DRAFT | STABLE | CHANGED
SURFACE:
  routes: []
  http_behavior: []
  template_variables: []
  dto_or_data_shape: []
  service_signatures: []
  events: []
INVARIANTS: []
VALIDATION: []
```

Backend/frontend ne démarrent en parallèle qu'avec un contrat `STABLE`. Tout changement de surface suspend les consommateurs, met le contrat à `CHANGED`, puis déclenche synchronisation et revalidation.

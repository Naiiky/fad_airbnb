# Machine d'état

L'Orchestrator seul possède `CURRENT_STATE` et effectue les transitions.

| De | Vers | Condition minimale |
|---|---|---|
| `RECEIVED` | `DISCOVERY` | demande enregistrée |
| `DISCOVERY` | `CLASSIFIED` | dépôt/instructions inspectés, classification établie |
| `CLASSIFIED` | `REQUIREMENTS_READY` | objectif, scope et critères testables |
| `CLASSIFIED` | `READY` | profil MICRO, besoin évident, critères inline, une work unit implicite et aucun contrat requis |
| `REQUIREMENTS_READY` | `PLANNED` | plan technique requis obtenu ou plan MICRO explicite |
| `PLANNED` | `READY` | DAG acyclique, owners, contrats et validations définis |
| `READY` | `EXECUTING` | work units prêtes sélectionnées |
| `EXECUTING` | `VALIDATING` | writers terminés, résultats intégrés |
| `VALIDATING` | `REVIEWING` | preuves applicables collectées |
| `VALIDATING` | `APPROVED` | profil MICRO, preuves ciblées collectées et aucune review requise |
| `REVIEWING` | `APPROVED` | aucun finding obligatoire ouvert, reviews requises approuvées |
| `APPROVED` | `DONE` | Definition of Done vérifiée et rapport prêt |
| `VALIDATING|REVIEWING` | `CHANGES_REQUIRED` | finding obligatoire accepté/ou maintenu |
| `CHANGES_REQUIRED` | `EXECUTING` | owner et correction identifiés, compteur < 3 |
| `CHANGES_REQUIRED` | `REQUIREMENTS_READY|PLANNED` | compteur = 3, RCA Lead terminée; retour à la phase correspondant à la cause avec requirements/plan/DAG actualisés |
| tout état non terminal | `BLOCKED` | décision indispensable/autorisation/environnement sans alternative sûre |
| `BLOCKED` | état précédent | blocker levé, état et preuves conservés |
| tout état non terminal | `FAILED` | erreur irréversible ou stratégie épuisée après analyse |

`BLOCKED` décrit un obstacle récupérable et précis. `FAILED` n'est pas un synonyme de test rouge. Une validation impossible devient `UNABLE_TO_VALIDATE` et bloque seulement si elle est exigée pour Done.

Après RCA, l'Orchestrator conserve l'historique du finding, remplace la work unit ou le plan défectueux, puis crée un nouvel ID seulement si cause et changement attendu sont réellement différents.

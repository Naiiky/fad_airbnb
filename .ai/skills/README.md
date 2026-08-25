# Catalogue des skills

Chaque skill se trouve dans `skills/<nom>/SKILL.md`. L'Orchestrateur lit uniquement le skill correspondant à l'opération demandée et le transmet au writer concerné. Ce placement privilégie la structure mono-dossier demandée; il n'est pas auto-découvert par le sélecteur natif `$skill` de Codex.

| Skill | Usage principal |
|---|---|
| `create-controller` | route et contrôleur fin |
| `create-entity` | mapping et invariants Doctrine |
| `create-form` | formulaire Symfony et validation |
| `create-migration` | évolution de schéma sûre |
| `create-voter` | autorisation objet |
| `create-service` | logique métier cohésive |
| `create-functional-test` | comportement HTTP/intégration |
| `create-unit-test` | logique isolée |
| `review-doctrine-query` | performance/intégrité des requêtes |
| `validate-symfony-feature` | pipeline adaptatif final |
| `inspect-existing-feature` | tracer un flux et trouver les patterns réutilisables |
| `analyze-doctrine-impact` | mesurer l'impact entités/requêtes/migration |
| `analyze-access-control` | cartographier et valider une frontière d'autorisation |
| `debug-failing-test` | diagnostiquer un test rouge sans correction aveugle |
| `review-template-accessibility` | auditer l'accessibilité d'un template/composant |

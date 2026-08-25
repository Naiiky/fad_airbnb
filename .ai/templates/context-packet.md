# Context packet

```text
TASK: résultat concret attendu
WORK_UNIT: identifiant et description du DAG
CLASSIFICATION: PRIMARY_TYPE / IMPACT_FLAGS / COMPLEXITY / RISK / EXECUTION_PROFILE
REQUIREMENTS: exigences et critères d'acceptation utiles
ACCEPTANCE_CRITERIA: résultats observables propres à cette unité
RELEVANT_FILES: liste ciblée avec raison
DEPENDENCIES: décisions ou sorties préalables
INTERFACE_CONTRACTS: contrats stables produits/consommés
DECISIONS: décisions utiles déjà prises
CONSTRAINTS: règles applicables, fichiers interdits, propriétaire des fichiers
KNOWN_FINDINGS: IDs pertinents uniquement, sinon omis
YOUR_RESPONSIBILITY: responsabilité unique de cet agent
ALLOWED_WRITE_SCOPE: fichiers exclusifs ou `READ_ONLY`
EXPECTED_OUTPUT: livrable et format du protocole
VALIDATION_EXPECTED: contrôles que cet agent doit exécuter
```

Ne transmettre ni historique brut, ni logs complets, ni fichiers sans rapport. Résumer les décisions antérieures sans leur raisonnement privé.

Avant écriture, le writer relit les fichiers importants du scope. Si leur état n'est plus compatible avec le packet, il suspend la modification et fait réévaluer dépendances, ownership ou contrat. Aucun système de versioning supplémentaire n'est requis.

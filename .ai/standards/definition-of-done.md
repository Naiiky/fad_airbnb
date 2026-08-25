# Definition of Done

Une tâche est terminée seulement si les éléments applicables sont prouvés :

- critères d'acceptation satisfaits et implémentation complète;
- architecture Symfony cohérente, sans duplication ni abstraction inutile;
- mappings, schéma, migration, indexes et transactions cohérents;
- autorisations, CSRF, validation, encodage et secrets contrôlés;
- tests ciblés réussis et absence de régression connue;
- container/Twig/configuration valides selon le périmètre;
- QA, Security et Code Review requis ont approuvé;
- aucun finding `BLOCKER|CRITICAL|MAJOR` n'est `OPEN|ACCEPTED|IN_PROGRESS|DISPUTED`;
- Task State en `APPROVED` avant transition finale `DONE`;
- chaque validation applicable possède une preuve structurée `VALIDATED`; les `NOT_APPLICABLE` sont justifiés;
- aucun debug, TODO accidentel, code mort ou changement hors périmètre introduit;
- rapport final listant fichiers, comportement, commandes, résultats et limites.

Une validation `UNABLE_TO_VALIDATE` doit préciser cause et risque restant; elle ne peut être présentée comme réussie et bloque Done si le contrôle est obligatoire.

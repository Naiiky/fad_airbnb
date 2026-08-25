# Règles Symfony

- Ordre de préférence : conventions Symfony 8, conventions du dépôt, PHP générique.
- Contrôleurs fins : orchestration HTTP seulement; métier dans des services cohésifs.
- Aucune logique métier dans Twig. Échapper par défaut; n'utiliser `raw` qu'avec contenu maîtrisé.
- Utiliser injection de dépendances, autowiring, autoconfiguration et attributes avant une configuration custom.
- Utiliser Validator pour les invariants d'entrée et Forms pour les formulaires serveur.
- Utiliser Security et Voters pour l'autorisation objet; ne jamais assimiler authentifié à autorisé.
- Entités centrées sur l'état et les invariants simples; requêtes dans les repositories; éviter N+1.
- Chaque évolution de schéma possède une migration relue, déterministe et cohérente avec les mappings.
- Encadrer par transaction les écritures atomiques multi-opérations; ne pas masquer les exceptions utiles.
- Événements/subscribers seulement pour un découplage réel, jamais pour cacher un flux simple.
- Configuration par environnement; secrets hors dépôt; aucun accès direct arbitraire aux variables dans le métier.
- Noms explicites, types stricts compatibles avec le dépôt, pas de code mort ni abstraction prématurée.
- Tests au niveau le moins coûteux qui prouve le comportement; test fonctionnel pour HTTP, sécurité et intégration.

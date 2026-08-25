# Règles de sécurité

- Vérifier authentification puis autorisation sur chaque ressource (`authenticated != authorized`).
- Prévenir IDOR avec Voter ou contrôle d'accès objet côté serveur; ne jamais faire confiance à un identifiant client.
- Activer et tester CSRF pour toute mutation navigateur; préférer les Forms Symfony.
- Valider, normaliser si nécessaire, puis encoder à la sortie; éviter HTML brut et requêtes concaténées.
- Utiliser Doctrine avec paramètres; interdire SQL/DQL construit depuis une entrée non fiable.
- Limiter les champs modifiables explicitement; ne pas hydrater aveuglément une entité depuis la requête.
- Pour les uploads : taille, MIME réel, extension, nom généré, stockage non exécutable et autorisation.
- Ne jamais exposer secrets, tokens, mots de passe, stack traces ou données sensibles dans les logs/réponses.
- Protéger sessions, cookies et mots de passe via les mécanismes Symfony; appliquer rate limiting aux surfaces abusables.
- Toute découverte `BLOCKER` ou `CRITICAL` déclenche veto et correction avant livraison.

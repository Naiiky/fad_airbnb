---
name: create-controller
description: Créer ou modifier un contrôleur et ses routes dans une application Symfony; ne pas utiliser pour de la logique métier isolée.
---

# Créer un contrôleur Symfony

1. Lire `.ai/rules/symfony.md`, `.ai/rules/security.md` et rechercher les contrôleurs/routes/tests existants.
2. Définir contrat HTTP, méthode, route, entrées, réponses, autorisation et erreurs avant d'éditer.
3. Garder le contrôleur fin : conversion HTTP, validation, appel de service, réponse.
4. Utiliser injection, attributes, Forms/Validator et Voters natifs selon le besoin.
5. Ajouter/adapter un test fonctionnel couvrant succès, entrée invalide et accès interdit pertinents.
6. Exécuter les validations ciblées de `.ai/standards/validation.md` et retourner le protocole commun.

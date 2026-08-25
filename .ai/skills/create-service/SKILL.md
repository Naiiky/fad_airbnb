---
name: create-service
description: Extraire ou créer un service Symfony pour une opération métier cohésive et testable.
---

# Créer un service

1. Rechercher un service ou composant natif Symfony réutilisable avant toute création.
2. Donner au service une responsabilité métier unique et une API explicite; éviter interfaces à implémentation unique sans besoin.
3. Injecter les dépendances par constructeur; ne pas injecter le conteneur ni lire directement la requête.
4. Définir limites transactionnelles, erreurs métier et effets de bord visibles.
5. Enregistrer par autowiring/autoconfiguration; config explicite seulement si nécessaire.
6. Ajouter tests unitaires ou d'intégration au niveau qui prouve réellement le comportement.

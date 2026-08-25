---
name: validate-symfony-feature
description: Construire et exécuter le pipeline de validation Symfony adapté aux fichiers modifiés avant livraison.
---

# Valider une fonctionnalité Symfony

1. Lire `.ai/standards/definition-of-done.md` et `.ai/standards/validation.md`.
2. Inventorier uniquement les fichiers modifiés et mapper les risques aux contrôles requis.
3. Confirmer l'existence des commandes, outils et dossiers avant exécution.
4. Lancer d'abord syntaxe/tests ciblés, puis container, Twig, Doctrine et suite large selon impact.
5. Exécuter PHPStan/PHP-CS-Fixer uniquement s'ils sont configurés; ne jamais les installer implicitement.
6. Retourner chaque commande, code/résultat, échec préexistant identifié et validation non exécutable. Ne jamais transformer « non exécuté » en succès.

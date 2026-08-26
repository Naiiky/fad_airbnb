# Tailwind Design Rule

Toute implémentation UI du projet doit utiliser Tailwind CSS 4 comme source unique de styling applicatif.

- Utiliser Twig + classes Tailwind pour les layouts, composants, états, espacements, couleurs, typographies et responsive.
- Garder `assets/styles/app.css` comme point d'entrée Tailwind et pour les directives Tailwind nécessaires, les `@source` et les tokens de thème globaux.
- Ne pas recréer de classes CSS métier pour styliser les écrans ou composants (`auth-*`, `card-*`, `button-*`, etc.) lorsqu'une composition Tailwind peut exprimer le design.
- Ne conserver du CSS custom que pour une contrainte globale Tailwind 4, un token de thème, une intégration build, ou un cas impossible à exprimer proprement en classes utilitaires.
- Quand une maquette Figma est fournie, Figma reste la source de vérité visuelle; traduire ses valeurs en classes Tailwind exactes ou arbitraires sans importer React, Vue ou autre framework.
- Réutiliser les assets Figma disponibles et les servir depuis un chemin projet stable (`public/` ou build frontend selon le pipeline actif).
- Signaler tout conflit entre la maquette Figma, Tailwind 4 et les conventions Symfony/Twig au lieu de redessiner silencieusement.

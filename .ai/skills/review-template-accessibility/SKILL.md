---
name: review-template-accessibility
description: Auditer l'accessibilité d'un template Twig ou composant UI après une modification visuelle ou interactive.
---

# Revoir l'accessibilité d'un template

1. Inspecter structure sémantique, titres, landmarks, labels, noms accessibles et ordre DOM.
2. Vérifier clavier, focus visible, états disabled/error/success et annonces pertinentes.
3. Contrôler contraste et information non portée par la couleur seule selon le design disponible.
4. Vérifier responsive/zoom et cohérence avec les composants existants.
5. Retourner findings localisés avec preuve; lancer lint Twig/test ciblé via le standard Docker si disponible.

# Classification et routage

## Classification multidimensionnelle

### PRIMARY_TYPE

`FEATURE`, `BUG`, `REFACTOR`, `DATABASE`, `SECURITY`, `UI`, `INFRASTRUCTURE`, `DOCUMENTATION`, `TEST`.

Choisir exactement un type principal. Un défaut d'autorisation signalé comme bug reste `BUG` avec flags `SECURITY` et `AUTHORIZATION`.

### IMPACT_FLAGS

Zéro ou plusieurs, liste extensible : `DATABASE`, `DOCTRINE`, `MIGRATION`, `SECURITY`, `AUTHORIZATION`, `AUTHENTICATION`, `PRIVACY`, `FILE_UPLOAD`, `UI`, `API`, `EMAIL`, `PERFORMANCE`, `INFRASTRUCTURE`, `TESTING`, `DOCUMENTATION`.

Les flags viennent du besoin et des chemins réellement trouvés, jamais d'une liste exécutée systématiquement.

### COMPLEXITY

- `TRIVIAL` : changement local, comportement évident, aucun schéma/API/permission.
- `STANDARD` : plusieurs fichiers d'une même couche, risque maîtrisé.
- `COMPLEX` : plusieurs couches, migration, flux métier ou dépendances multiples.
- `VERY_COMPLEX` : nombreuses dépendances/couches, architecture ou migration progressive significative.

### RISK

- `LOW` : réversible, faible rayon d'impact, aucune donnée/permission sensible.
- `MEDIUM` : régression plausible mais contrôlable par tests ciblés.
- `HIGH` : défaut/frontière d'autorisation sensible, données personnelles, migration risquée, paiement, email critique ou disponibilité. La présence d'une permission ordinaire correctement bornée ne suffit pas seule.
- `CRITICAL` : exploitation démontrée, perte de données, secret compromis ou opération difficilement réversible.

Complexité et risque sont indépendants. Une correction d'autorisation de trois lignes peut être `TRIVIAL/HIGH`.

## Profils d'exécution

- `MICRO` : `TRIVIAL` et risque `LOW`; inspection ciblée, un writer, validation ciblée, QA léger seulement si utile.
- `STANDARD` : travail classique `STANDARD|COMPLEX` et risque `LOW|MEDIUM`; plan léger, writers nécessaires, QA et reviews conditionnelles.
- `FULL` : `VERY_COMPLEX`, risque `HIGH|CRITICAL`, auth, sécurité importante, migration/architecture/infrastructure significative.

Choisir le profil le plus léger qui couvre le risque. Un flag sensible peut élever le profil même si la complexité est faible.

### Fast path MICRO

Classification inline, Task State minimal, une work unit/DAG implicite, aucun contrat sans frontière partagée, un writer et validations ciblées. Requirement Analyst, Team Lead, Architect, DB, Security et Code Reviewer sont `SKIP` sauf signal concret qui change classification ou risque.

### Routing des reviewers

- Security : requis pour SECURITY, AUTHENTICATION, AUTHORIZATION, FILE_UPLOAD, session/mot de passe/donnée sensible ou risque sécurité HIGH+; jamais pour UI sans impact sécurité.
- Code Review : requis pour COMPLEX+, refactor, architecture, métier important, transversal ou risque de maintenabilité; pas automatique en MICRO.
- QA : ciblé/optionnel en MICRO, fonctionnel adapté en STANDARD, complet mais limité au périmètre en FULL.

Avant chaque invocation : « Quelle valeur unique cet agent ajoute-t-il ? ». Si aucune réponse concrète : `SKIP`.

## Composition des workflows

Le workflow principal est choisi uniquement par `PRIMARY_TYPE`. Les flags activent des obligations en overlay depuis les workflows correspondants sans remplacer le principal : agents spécialistes, dépendances, reviews, validations et conditions de sortie. En cas de conflit, appliquer la contrainte la plus sûre sans dupliquer les phases. Exemple : `FEATURE + DATABASE + AUTHORIZATION` suit `feature.md`, enrichi des obligations DB et sécurité pertinentes.

## Sélection minimale

| Signal | Agents requis | Optionnels |
|---|---|---|
| Texte/style Twig `MICRO` | Frontend | QA léger si comportement |
| Feature `STANDARD` | Lead, développeur concerné, QA | Analyst si ambigu; reviewers selon flags |
| Doctrine/migration | DB, Lead, Symfony, QA | Architect si structure; Security si sensible |
| Autorisation/auth | Security, Symfony, QA | Lead si plan transverse; Analyst, DB, Frontend, Code Review selon impacts |
| Refactor | Lead, implémenteur, QA | Architect si frontières; DB si requêtes; Code Review |
| Infrastructure | DevOps, QA ciblé | Lead, Security, Architect selon risque |

L'Architecte intervient uniquement pour une décision structurelle significative. Security intervient dès qu'identité, autorisation, secret, entrée non fiable, upload ou donnée sensible sont touchés.

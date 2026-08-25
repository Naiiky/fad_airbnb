# Benchmark de routage et sur-orchestration

| Scénario | Classification | Graphe minimal attendu |
|---|---|---|
| A. Texte Connexion | UI; UI; TRIVIAL; LOW; MICRO | Frontend; validation ciblée; tous spécialistes/reviewers SKIP |
| B. Couleur bouton | UI; UI; TRIVIAL; LOW; MICRO | Frontend; contrôle ciblé; QA seulement si comportement |
| C. Téléphone facultatif profil | FEATURE; DATABASE/DOCTRINE/UI/TESTING; STANDARD présumé; MEDIUM; STANDARD | Inspection d'abord; Lead si plusieurs couches, DB + Symfony + Frontend, QA; risque/profil réévalués selon modèle réel |
| D. Favoris logements | FEATURE; DATABASE/DOCTRINE/UI/AUTHORIZATION/TESTING; COMPLEX; MEDIUM; STANDARD | Lead -> DB analysis || UX utile -> contrat partagé -> backend || frontend si stable -> QA -> Security ciblée |
| E. Modification logement d'autrui | BUG; SECURITY/AUTHORIZATION/TESTING; TRIVIAL ou STANDARD; HIGH; FULL | Security triage -> Symfony (+ Lead si transverse) -> QA || Security; Code Review selon portée |
| F. Refactor réservation | REFACTOR; TESTING; COMPLEX; MEDIUM; STANDARD | inspection + Lead -> Architect seulement si frontières -> writer -> QA -> Code Review |
| G. Auth + vérification email | FEATURE; SECURITY/AUTHENTICATION/DATABASE/UI/EMAIL/TESTING; VERY_COMPLEX; HIGH; FULL | Analyst -> Lead -> DB + Security -> Architect conditionnel -> contrat -> Symfony + Frontend -> QA || Security -> Code Review |
| H. Suppression compte | FEATURE; DATABASE/DOCTRINE/SECURITY/AUTHORIZATION/PRIVACY/TESTING; COMPLEX; HIGH; FULL | inspection relations -> Analyst/DB/Security -> décision métier -> plan -> writers -> reviews; question possible |
| I. MySQL indisponible | TEST; INFRASTRUCTURE/TESTING; TRIVIAL; MEDIUM; STANDARD ciblé | `ENVIRONMENT_FAILURE`; DevOps/diagnostic, aucun changement PHP; validation `UNABLE_TO_VALIDATE` |
| J. Classe Tailwind | UI; UI; TRIVIAL; LOW; MICRO | Frontend, validation ciblée; Security/Architect/DB/Code Review SKIP |
| K. Condition de suppression logement | SECURITY; SECURITY/AUTHORIZATION/TESTING; TRIVIAL; HIGH; FULL ciblé | Security triage -> Symfony -> test de régression -> Security; Lead seulement si portée transverse, Code Review seulement si diff/logique le justifie |
| L. Message d'erreur dupliqué | BUG; UI/TESTING; TRIVIAL présumé; LOW; MICRO | inspection ciblée -> writer de la couche trouvée -> validation de non-duplication; équipe et reviews supplémentaires SKIP sans signal |

Pour chaque ligne, enregistrer `AGENTS_USED`, `AGENTS_SKIPPED`, `WORK_UNITS`, `VALIDATIONS`, `USER_QUESTIONS`. A/B/J utilisent un Task State minimal, DAG implicite et aucun contrat. H ne choisit jamais silencieusement une politique irréversible si plusieurs règles métier incompatibles subsistent.

## Mesure de coût attendue

| Cas | AGENTS_USED | AGENTS_SKIPPED principaux | WORK_UNITS | VALIDATIONS | USER_QUESTIONS |
|---|---|---|---:|---|---:|
| A | Frontend | Analyst, Lead, Architect, DB, Security, Code Review | 1 implicite | Twig ciblé si disponible | 0 |
| B | Frontend | Analyst, Lead, Architect, DB, Security, Code Review | 1 implicite | rendu/classe ciblé | 0 |
| C | inspection, DB, Symfony, Frontend, QA; Lead conditionnel | Architect, Security sauf signal | 3–5 | schéma/Form/Twig/tests disponibles | 0 sauf règle profil irréversible |
| D | Lead, DB, Symfony, Frontend, QA, Security ciblée | Architect sauf décision; UI/UX si aucun choix UX | 5–7 | Doctrine, accès, UI, tests disponibles | 0 |
| E | Security, Symfony, QA; Lead/Code conditionnels | DB/Frontend/Architect sans impact | 3–5 | régression autorisation | 0 |
| F | inspection, Lead, writer, QA, Code Review | Analyst/DB/Security sans signal; Architect conditionnel | 4–6 | caractérisation + régression | 0 sauf comportement indéductible |
| G | Analyst, Lead, DB, Security, Symfony, Frontend, QA, Code Review; Architect conditionnel | agents sans responsabilité concrète | 8–10 | auth/email/DB/UI + suite justifiée | 0–1 |
| H | inspection, Analyst, DB, Security, Lead puis writers/reviewers | UI/UX/Architect sans signal | 6–9 | relations, privacy, auth, tests | 1 si politique métier indéductible |
| I | diagnostic environnement, DevOps si action requise | Symfony/Frontend/Architect/reviewers produit | 1–2 | état Docker/MySQL; produit non validable | 0 sauf autorisation externe |
| J | Frontend | Analyst, Lead, Architect, DB, Security, Code Review | 1 implicite | contrôle ciblé | 0 |
| K | Security, Symfony, QA, Security re-review | Analyst, Architect, DB, Frontend; Lead/Code Review sans signal | 3–4 | cas propriétaire/non-propriétaire + contrôle serveur | 0 |
| L | writer ciblé | Analyst, Lead, Architect, DB, Security, Code Review; QA séparé si preuve locale suffisante | 1 implicite | reproduction ciblée avant/après | 0 |

Les fourchettes sont des plafonds indicatifs, pas des quotas. Tout agent, work unit, contrat, review ou validation sans valeur observable est retiré.

## Simulation de parallélisation

Cas valide — Favoris, après `IC-001 STABLE` définissant route, méthode HTTP, variable Twig et état retourné : `T1` Database Expert read-only (`src/Entity`, migrations; sortie contraintes) || `T2` UI/UX read-only (`templates` ciblés; sortie états). Puis `T3` backend possède PHP/Doctrine et `T4` frontend possède Twig/JS; leurs outputs respectent `IC-001`. Synchronisation avant `T5 QA`.

Contre-exemple — Backend invente encore route et structure de réponse tandis que Frontend veut modifier le template consommateur. Fichiers disjoints, mais contrat `DRAFT`; T4 dépend de T3/IC-001 et doit être sérialisée.

## Simulation de correction/arbitrage

1. QA émet `QA-001 MAJOR OPEN`; writer l'accepte, compteur `QA-001=1`, corrige, marque `RESOLVED`; QA revalide et approuve.
2. Security émet `SEC-001 MAJOR OPEN`; writer répond `DISPUTED` avec preuve; Lead arbitre `UPHELD` si le contrôle serveur manque, sinon `DISMISSED` si la preuve montre une autorisation effective. Un `UPHELD` incrémente seulement `SEC-001`; `DISMISSED` ferme sans correction.

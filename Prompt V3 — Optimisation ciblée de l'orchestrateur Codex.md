# MISSION

Tu es un architecte logiciel senior spécialisé dans :

- Codex ;
- orchestration multi-agents ;
- systèmes autonomes d'agents IA ;
- Symfony 8 ;
- PHP moderne ;
- Doctrine ORM ;
- Twig ;
- Tailwind CSS ;
- Docker ;
- tests automatisés ;
- sécurité applicative ;
- optimisation du contexte LLM.

Un orchestrateur multi-agents existe déjà dans ce repository.

Il a déjà fait l'objet d'une première refonte importante.

NE LE RECRÉE PAS.

NE RECONSTRUIS PAS L'ARCHITECTURE DE ZÉRO.

Ta mission est d'effectuer une optimisation ciblée de l'orchestrateur existant afin de corriger ses derniers risques structurels sans augmenter inutilement sa complexité.

Le système possède déjà notamment des concepts de :

- Orchestrator ;
- agents spécialisés ;
- Task State ;
- state machine ;
- Task Graph / DAG ;
- ownership ;
- interface contracts ;
- context packets ;
- findings ;
- workflows ;
- execution profiles ;
- Definition of Ready ;
- Definition of Done ;
- validation evidence ;
- project knowledge ;
- Docker execution.

Ces concepts doivent être CONSERVÉS lorsqu'ils sont correctement implémentés.

Principe principal de cette intervention :

> SIMPLIFY, CLARIFY AND HARDEN. DO NOT EXPAND.

L'objectif n'est plus d'ajouter des mécanismes.

L'objectif est de rendre les mécanismes existants :

- plus légers ;
- plus déterministes ;
- plus conditionnels ;
- plus efficaces ;
- moins consommateurs de contexte ;
- mieux adaptés aux vraies tâches Symfony ;
- réellement exploitables par Codex.

---

# 1. INSPECTION OBLIGATOIRE

Avant toute modification, inspecte intégralement :

- `AGENTS.md` ;
- `.ai/README.md` ;
- `.ai/agents/` ;
- `.ai/rules/` ;
- `.ai/protocol/` ;
- `.ai/workflows/` ;
- `.ai/skills/` ;
- `.ai/standards/` ;
- `.ai/templates/` ;
- `.ai/project/` ;
- la configuration Codex éventuelle ;
- `docker-compose.yml` ;
- les Dockerfiles ;
- `apache/` ;
- `db/` ;
- l'application Symfony dans `www/skeleton`.

Comprends les références entre fichiers avant de les modifier.

Pour chaque changement envisagé, applique :

KEEP  
IMPROVE  
SIMPLIFY  
MERGE  
REMOVE

Ne modifie pas un fichier uniquement pour changer sa formulation.

Ne duplique jamais une règle déjà centralisée.

---

# 2. OBJECTIF PRINCIPAL : ÉVITER LA SUR-ORCHESTRATION

Le principal risque actuel est qu'une tâche simple déclenche trop de mécanismes.

Le système doit respecter :

> The orchestration cost must be proportional to task complexity and risk.

Une tâche triviale ne doit pas déclencher une simulation complète d'équipe.

Le système doit disposer de profils réellement différents.

---

# 3. EXECUTION PROFILE MICRO

Vérifie et renforce le profil MICRO.

MICRO doit être un véritable FAST PATH.

Il est destiné notamment à :

- modification de texte ;
- changement de classe Tailwind ;
- petite modification Twig ;
- correction visuelle locale ;
- changement de configuration trivial et faible risque ;
- petite correction clairement localisée.

Pour MICRO, autoriser :

- classification inline ;
- Task State minimal ;
- DAG implicite à une work unit ;
- aucun Interface Contract si inutile ;
- aucun Requirement Analyst sauf ambiguïté réelle ;
- aucun Team Lead sauf nécessité ;
- aucun Architect ;
- aucun Database Expert sans impact DB ;
- aucun Security Reviewer sans impact sécurité ;
- aucun Code Reviewer sans justification ;
- un seul writer ;
- validation ciblée.

Ne matérialise pas artificiellement des structures vides.

Exemple :

"Remplace Connexion par Se connecter."

doit être proche de :

DISCOVERY ciblé  
→ Frontend Developer  
→ validation Twig ciblée si pertinente  
→ DONE

et non :

Orchestrator  
→ Requirement Analyst  
→ Team Lead  
→ DAG complexe  
→ Frontend  
→ QA complet  
→ Security  
→ Code Review  
→ Team Lead  
→ DONE

Principe :

> Trivial work must remain trivial.

---

# 4. EXECUTION PROFILE STANDARD

STANDARD est le profil normal pour une fonctionnalité ou correction classique.

Il peut utiliser :

- Requirement Analyst si le besoin nécessite une formalisation ;
- Team Lead si plusieurs composants sont concernés ;
- spécialistes selon impact ;
- writers nécessaires ;
- QA ;
- reviews conditionnelles.

Aucun agent n'est obligatoire uniquement parce qu'il existe.

Chaque invocation doit répondre à :

> What unique value does this agent add to this task?

Si la réponse est insuffisante :

SKIP.

---

# 5. EXECUTION PROFILE FULL

FULL est réservé aux tâches réellement importantes :

- sécurité ;
- authentification ;
- autorisation sensible ;
- migration complexe ;
- changement architectural ;
- feature multi-domaines ;
- infrastructure ;
- données sensibles ;
- risque élevé ;
- refactoring important.

FULL peut déclencher l'équipe complète pertinente.

Même en FULL, n'invoque jamais un agent sans responsabilité concrète.

---

# 6. ROUTING PAR TYPE + IMPACT + COMPLEXITÉ + RISQUE

Vérifie que le routing utilise réellement quatre dimensions distinctes.

## PRIMARY_TYPE

FEATURE  
BUG  
REFACTOR  
DATABASE  
SECURITY  
UI  
INFRASTRUCTURE  
DOCUMENTATION  
TEST

## IMPACT_FLAGS

Plusieurs flags peuvent être actifs simultanément.

Exemples :

DATABASE  
DOCTRINE  
MIGRATION  
UI  
AUTHENTICATION  
AUTHORIZATION  
SECURITY  
PRIVACY  
FILE_UPLOAD  
EMAIL  
PERFORMANCE  
INFRASTRUCTURE  
TESTING

N'impose pas une liste artificiellement fermée si le repository nécessite un impact pertinent supplémentaire.

## COMPLEXITY

TRIVIAL  
STANDARD  
COMPLEX  
VERY_COMPLEX

## RISK

LOW  
MEDIUM  
HIGH  
CRITICAL

Ne confonds jamais complexité et risque.

Exemple :

Modifier une seule condition d'autorisation peut être :

COMPLEXITY: TRIVIAL

RISK: HIGH

Le routing des agents et validations doit dépendre de l'ensemble de ces dimensions.

---

# 7. ROUTING DES REVIEWERS

Les reviewers ne doivent PAS être systématiques.

## SECURITY REVIEWER

Obligatoire lorsque pertinent, notamment si :

- `SECURITY` impact ;
- `AUTHENTICATION` impact ;
- `AUTHORIZATION` impact ;
- données sensibles ;
- upload de fichiers ;
- changement de session ;
- mot de passe ;
- contrôle d'accès ;
- risque HIGH/CRITICAL lié à la sécurité.

Ne pas invoquer pour une modification UI sans impact sécurité.

## CODE REVIEWER

Doit être principalement utilisé pour :

- changement complexe ;
- refactoring ;
- architecture ;
- logique métier importante ;
- changement transversal ;
- code présentant un risque significatif de maintenabilité.

Ne pas l'utiliser automatiquement sur une modification triviale.

## QA ENGINEER

Adapter son niveau d'intervention au profil.

MICRO :
validation ciblée ou QA léger si nécessaire.

STANDARD :
QA fonctionnel adapté.

FULL :
QA complet pertinent.

---

# 8. FRONTIÈRE ORCHESTRATOR / TEAM LEAD

Inspecte attentivement les deux agents.

Supprime toute responsabilité ambiguë.

## REQUIREMENT ANALYST

Répond :

> WHAT does the user actually need?

Produit :

- requirements ;
- acceptance criteria ;
- business ambiguities ;
- edge cases pertinents.

Il ne choisit pas l'architecture.

## TEAM LEAD

Répond :

> WHAT technical work is required?

Produit :

- plan technique ;
- work units proposées ;
- dépendances techniques ;
- zones impactées ;
- risques techniques ;
- recommandations de spécialistes.

Il ne pilote pas l'exécution globale.

Il ne devient pas un deuxième Orchestrator.

## ORCHESTRATOR

Répond :

> HOW and WHEN will the work be executed, and by WHOM?

Il possède :

- Task State ;
- state machine ;
- sélection finale des agents ;
- Execution Profile ;
- DAG final ;
- scheduling ;
- context packets ;
- ownership ;
- parallélisation ;
- collecte des résultats ;
- correction loops ;
- arbitrage ;
- validation finale ;
- passage à DONE.

Principe obligatoire :

> Requirement Analyst defines the need.
> Team Lead designs the technical work.
> Orchestrator governs execution.

---

# 9. TASK STATE : MINIMAL ET ACTUEL

Inspecte `.ai/templates/task-state.md`.

Le Task State doit être une SOURCE DE VÉRITÉ SYNTHÉTIQUE.

Il ne doit jamais devenir :

- journal de conversation ;
- historique complet ;
- chaîne de raisonnement ;
- copie des réponses des agents ;
- documentation narrative.

Il doit contenir uniquement l'état opérationnel actuel nécessaire.

Conserve uniquement les informations utiles telles que :

TASK_ID  
USER_REQUEST  
CURRENT_STATE  
PRIMARY_TYPE  
IMPACT_FLAGS  
COMPLEXITY  
RISK  
REQUIREMENTS  
ACCEPTANCE_CRITERIA  
DECISIONS  
ASSUMPTIONS  
CONSTRAINTS  
TASK_GRAPH  
OWNERSHIP  
INTERFACE_CONTRACTS  
OPEN_FINDINGS  
VALIDATION_EVIDENCE  
BLOCKERS

Les résultats obsolètes doivent être remplacés par leur conclusion actuelle plutôt qu'accumulés indéfiniment.

Principe :

> Store conclusions, not conversations.

---

# 10. TASK STATE ADAPTATIF

Le Task State ne doit pas avoir la même taille pour toutes les tâches.

MICRO :

utiliser uniquement les champs nécessaires.

STANDARD :

utiliser la structure normale.

FULL :

utiliser la structure complète nécessaire.

Les champs inutiles peuvent être :

N/A

ou omis si le protocole le permet.

Ne génère jamais des dizaines de lignes vides uniquement pour respecter un template.

---

# 11. DAG MINIMAL

Le Task Graph sert uniquement à répondre :

- quelles work units existent ?
- qui en est responsable ?
- lesquelles dépendent des autres ?
- lesquelles peuvent réellement être parallélisées ?

N'ajoute aucune métadonnée sans utilité opérationnelle.

Chaque work unit peut contenir au minimum :

ID  
DESCRIPTION  
OWNER  
MODE  
DEPENDS_ON  
RELEVANT_SCOPE  
STATUS

Et uniquement lorsque nécessaire :

INTERFACE_CONTRACT  
VALIDATION

Pour une tâche MICRO à un seul writer :

un DAG implicite est acceptable.

Ne crée pas artificiellement un graphe à un nœud si cela n'apporte aucune valeur.

---

# 12. OWNERSHIP À DEUX NIVEAUX

Conserve :

> One active writer per file.

Mais distingue :

## FILE OWNERSHIP

Qui peut modifier quel fichier pendant une phase.

## BOUNDARY OWNERSHIP

Qui définit une interface consommée par une autre work unit.

Exemples :

- route Symfony ;
- variable Twig ;
- service public ;
- DTO ;
- événement ;
- structure de données ;
- contrat HTTP.

Cela doit empêcher deux agents de produire des fichiers différents mais incompatibles.

---

# 13. INTERFACE CONTRACTS UNIQUEMENT LORSQUE NÉCESSAIRES

Ne crée PAS un Interface Contract pour chaque work unit.

Règle :

> Create an interface contract only when independently executed work units share a boundary.

Exemple utile :

Backend et Frontend travaillent en parallèle.

Le contrat peut préciser :

ROUTE  
HTTP_METHOD  
INPUT  
OUTPUT  
TWIG_VARIABLES  
EXPECTED_BEHAVIOR

Exemple inutile :

un Symfony Developer réalise seul une petite feature backend.

Dans ce cas :

INTERFACE CONTRACT = inutile.

Évite toute bureaucratie artificielle.

---

# 14. STALE CONTEXT PROTECTION

Ajoute une règle simple contre le contexte périmé.

Avant qu'un writer modifie un fichier important :

- vérifier que l'état actuel du fichier reste compatible avec les hypothèses du context packet ;
- si le fichier a changé depuis la préparation de la work unit, réévaluer les éléments concernés.

Les reviewers doivent analyser :

1. requirements ;
2. diff/changements actuels ;
3. fichiers directement dépendants ;
4. contexte supplémentaire uniquement si nécessaire.

Principe :

> Review the current repository state, not a stale snapshot.

Ne construis pas de système complexe de versioning.

---

# 15. CONTEXT PACKETS MINIMAUX

Inspecte le template existant.

Chaque agent reçoit uniquement ce qui est nécessaire.

Priorité :

TASK  
WORK_UNIT  
REQUIREMENTS pertinents  
ACCEPTANCE_CRITERIA pertinents  
RELEVANT_FILES  
DEPENDENCIES  
CONTRACTS pertinents  
DECISIONS pertinentes  
CONSTRAINTS  
ALLOWED_WRITE_SCOPE  
EXPECTED_OUTPUT

Ne transmets pas :

- tout le repository ;
- tout le Task State si inutile ;
- tout le raisonnement précédent ;
- tous les findings sans rapport avec la mission.

Principe :

> Context is a budget.

---

# 16. REVIEW DIFF-FIRST

QA, Security Reviewer et Code Reviewer doivent utiliser une stratégie :

REQUIREMENTS  
→ CHANGED FILES / DIFF  
→ DIRECT DEPENDENCIES  
→ ADDITIONAL CONTEXT IF REQUIRED

Ils ne doivent pas rescanner tout le repository systématiquement.

Exception :

si la nature du changement exige une analyse transversale.

Exemples :

- authentification ;
- architecture ;
- permissions globales ;
- refactor transversal.

---

# 17. FINDINGS ACTIONNABLES

Inspecte `.ai/protocol/findings.md`.

Chaque finding important doit posséder un ID stable.

Exemples :

QA-001  
SEC-001  
CODE-001  
ARCH-001

Structure minimale :

ID  
SOURCE  
CATEGORY  
SEVERITY  
STATUS  
LOCATION  
PROBLEM  
EVIDENCE  
REQUIRED_OUTCOME  
OWNER

IMPORTANT :

Utilise `REQUIRED_OUTCOME`.

Évite que les reviewers imposent inutilement une implémentation.

Mauvais :

REQUIRED_CHANGE:
"Créer un PropertyVoter."

Bon :

REQUIRED_OUTCOME:
"Un utilisateur ne doit jamais pouvoir modifier un logement qu'il n'est pas autorisé à modifier."

Le writer reste responsable du choix de l'implémentation conforme aux conventions Symfony et au repository.

---

# 18. DISPUTED DOIT RESTER EXCEPTIONNEL

Conserve la possibilité de contester un finding.

Mais `DISPUTED` ne doit être utilisé que si :

- l'evidence est incorrecte ;
- le finding repose sur un état obsolète ;
- le finding contredit les requirements ;
- le finding est hors scope ;
- le required outcome est déjà satisfait ;
- le reviewer a mal interprété le comportement réel.

Ne jamais utiliser DISPUTED simplement parce que le writer préfère son implémentation.

En cas de DISPUTED justifié :

Reviewer finding  
→ Writer DISPUTED + evidence  
→ Team Lead arbitration

Résultats :

UPHELD  
DOWNGRADED  
DISMISSED

---

# 19. CORRECTION LOOPS

Conserve :

maximum 3 corrections automatiques par FINDING_ID.

Après chaque correction :

- réexécuter uniquement les validations pertinentes ;
- ne pas recommencer toute l'orchestration ;
- mettre à jour le finding ;
- conserver son ID.

Après 3 échecs :

Team Lead root-cause analysis.

Catégories possibles :

REQUIREMENTS  
PLAN  
ARCHITECTURE  
IMPLEMENTATION  
TEST  
REVIEW  
ENVIRONMENT  
DEPENDENCY  
TOOLING

Ne jamais répéter aveuglément la même correction.

---

# 20. CLASSIFIER LES ÉCHECS

Introduis ou vérifie une distinction entre :

IMPLEMENTATION_FAILURE  
VALIDATION_FAILURE  
ENVIRONMENT_FAILURE  
TOOLING_FAILURE  
DEPENDENCY_FAILURE

Exemple :

PHPUnit échoue parce que MySQL est indisponible.

Ce n'est PAS automatiquement :

IMPLEMENTATION_FAILURE.

C'est potentiellement :

ENVIRONMENT_FAILURE.

L'Orchestrator ne doit pas demander au Symfony Developer de modifier du code si la cause réelle est Docker ou MySQL.

---

# 21. VALIDATION EVIDENCE

Une validation automatisée doit produire lorsque possible :

COMMAND  
EXIT_CODE  
RESULT  
SCOPE

Statuts :

VALIDATED  
FAILED  
NOT_APPLICABLE  
UNABLE_TO_VALIDATE

Ne jamais inventer une validation.

Si une commande n'a pas pu être exécutée :

UNABLE_TO_VALIDATE

avec raison.

---

# 22. DOCKER : SOURCE DE VÉRITÉ D'EXÉCUTION

Le projet fonctionne avec Docker.

L'application Symfony est actuellement située dans :

`www/skeleton`

Inspecte `docker-compose.yml` avant toute hypothèse.

Détermine :

- service applicatif réel ;
- service DB réel ;
- working directory ;
- volumes ;
- PHP ;
- Composer ;
- commandes disponibles ;
- dépendances nécessaires aux tests.

Ne hardcode jamais un container ID ou un nom arbitraire.

Privilégie :

`docker compose ...`

selon la configuration réelle du repository.

Les commandes Symfony/PHP/Composer doivent normalement être exécutées DANS le conteneur applicatif.

Exemples conceptuels seulement :

`docker compose exec <app-service> php bin/console ...`

`docker compose exec <app-service> php bin/phpunit`

`docker compose exec <app-service> composer ...`

Détermine les vraies commandes depuis le repository.

---

# 23. DOCKER NON DISPONIBLE

Avant une validation nécessitant l'environnement :

vérifier l'état des services Docker lorsque pertinent.

Si Docker ou une dépendance nécessaire est indisponible :

ne jamais inventer un résultat.

Produire :

UNABLE_TO_VALIDATE

ou :

ENVIRONMENT_FAILURE

selon le contexte.

Ne modifie pas le code métier pour compenser un problème d'environnement.

---

# 24. CENTRALISER DOCKER

Ne duplique pas les instructions Docker dans chaque :

- agent ;
- workflow ;
- skill.

La stratégie Docker doit être centralisée dans la règle/standard approprié.

Les autres fichiers doivent la référencer.

Objectif :

une seule source de vérité concernant l'exécution des commandes applicatives.

---

# 25. VALIDATION CIBLÉE

Renforce le principe :

> Validate what changed.

Ne lance pas automatiquement tout le pipeline pour chaque modification.

Exemples conceptuels :

## TWIG

- lint Twig pertinent ;
- tests ciblés si existants.

## SERVICE SYMFONY

- tests concernés ;
- container lint si pertinent.

## DOCTRINE / ENTITY

- schema validation ;
- migration checks ;
- tests concernés.

## SECURITY

- tests d'autorisation/authentification concernés ;
- QA ;
- Security Review.

## CONFIGURATION SERVICES

- container lint.

## FULL / HIGH RISK

- validations plus larges si justifiées.

Les commandes doivent toujours respecter Docker.

---

# 26. PROJECT KNOWLEDGE : ÉVITER L'ACCUMULATION

Inspecte :

`.ai/project/architecture.md`  
`.ai/project/conventions.md`  
`.ai/project/decisions.md`

Ces fichiers doivent rester petits et durables.

## architecture.md

Doit décrire l'architecture RÉELLEMENT observée.

Pas une architecture idéale.

Évite les déclarations génériques du type :

"Le projet suit SOLID."

Privilégie des faits utiles.

## conventions.md

Uniquement les conventions réellement propres au repository et utiles aux futures tâches.

## decisions.md

Uniquement les décisions :

- durables ;
- transversales ;
- difficiles à redéduire ;
- susceptibles d'influencer plusieurs futures fonctionnalités.

Ne stocke pas les décisions locales triviales.

Principe :

> If the code already makes the decision obvious, do not duplicate it.

---

# 27. NE PAS AJOUTER DE NOUVEAUX AGENTS

Le nombre actuel d'agents est suffisant.

N'ajoute aucun nouvel agent sauf défaut critique impossible à résoudre autrement.

Les préoccupations spécialisées supplémentaires doivent généralement devenir :

- RULE ;
- SKILL ;
- STANDARD ;
- checklist intégrée à un reviewer existant.

Ne crée pas :

- Performance Engineer ;
- Accessibility Agent ;
- Doctrine Reviewer ;
- Testing Architect ;
- Documentation Reviewer ;

simplement pour spécialiser davantage.

Favorise une équipe petite et claire.

---

# 28. SKILLS : PRIVILÉGIER L'ANALYSE RÉUTILISABLE

Inspecte les skills existants.

Conserve les skills de création réellement utiles.

Mais vérifie que le système possède suffisamment de méthodes réutilisables pour :

- inspecter une feature existante ;
- tracer un flux ;
- rechercher un pattern existant ;
- analyser un impact ;
- analyser une relation Doctrine ;
- analyser une boundary de sécurité ;
- valider une autorisation ;
- diagnostiquer un test en échec.

Ne crée ces skills que s'ils apportent réellement une méthode réutilisable.

Ne transforme pas chaque action possible en skill.

---

# 29. SEARCH BEFORE CREATE

Conserve impérativement :

SEARCH  
→ UNDERSTAND  
→ REUSE  
→ MODIFY  
→ CREATE

Avant de créer une nouvelle abstraction, vérifier :

- service existant ;
- controller existant ;
- repository ;
- voter ;
- form ;
- Twig component ;
- JavaScript ;
- pattern similaire ;
- tests existants.

---

# 30. AUTONOMIE RAISONNÉE

L'objectif reste une autonomie maximale.

Mais autonomie ne signifie pas :

> Never ask the user.

Elle signifie :

> Ask only when proceeding autonomously would require an important unsupported product/business decision.

Avant de poser une question :

1. inspecter le repository ;
2. inspecter les tests ;
3. rechercher les conventions ;
4. analyser le comportement existant ;
5. chercher une décision durable existante ;
6. évaluer si une hypothèse est réversible.

Si :

LOW RISK + REVERSIBLE

→ choisir une hypothèse raisonnable.

Si :

HIGH RISK / IRREVERSIBLE / BUSINESS AMBIGUITY

→ demander à l'utilisateur si nécessaire.

---

# 31. SCÉNARIO CRITIQUE : SUPPRESSION DE COMPTE

Teste spécifiquement :

"Ajoute la possibilité de supprimer son compte."

Le système doit inspecter :

- User ;
- relations Doctrine ;
- FK ;
- données possédées ;
- réservations ;
- logements ;
- éventuels messages ;
- authentification ;
- sécurité ;
- logique existante de suppression/anonymisation.

Il ne doit PAS décider arbitrairement d'un comportement irréversible si plusieurs règles métier plausibles existent.

Exemple :

supprimer les logements ?

les conserver ?

les anonymiser ?

bloquer la suppression ?

Si le repository ne permet pas de le déduire et que plusieurs comportements ont un impact métier important :

demander à l'utilisateur.

C'est un comportement attendu, pas un échec d'autonomie.

---

# 32. BENCHMARK OBLIGATOIRE

Après les modifications, simule ou exécute selon ce qui est raisonnablement possible les scénarios suivants.

Pour chaque scénario, vérifie :

- PRIMARY_TYPE ;
- IMPACT_FLAGS ;
- COMPLEXITY ;
- RISK ;
- PROFILE ;
- agents invoqués ;
- agents ignorés ;
- DAG ;
- validations ;
- questions utilisateur éventuelles.

## A

"Remplace Connexion par Se connecter."

Attendu :

MICRO.

Très peu d'orchestration.

## B

"Change la couleur du bouton principal."

Attendu :

MICRO + UI.

## C

"Ajoute un champ téléphone facultatif au profil."

Attendu :

analyse réelle de l'impact Doctrine/Form/Twig avant routing.

## D

"Ajoute les favoris aux logements."

Attendu :

FEATURE avec impacts multiples.

## E

"Un utilisateur peut modifier le logement d'un autre."

Attendu :

BUG + SECURITY/AUTHORIZATION + risque élevé.

## F

"Refactorise le système de réservation."

Attendu :

inspection approfondie avant toute architecture.

## G

"Ajoute une authentification complète avec vérification email."

Attendu :

FULL.

## H

"Ajoute la suppression de compte."

Attendu :

analyse DB + SECURITY + PRIVACY + éventuelle question métier.

## I

"Les tests échouent parce que MySQL n'est pas disponible."

Attendu :

ENVIRONMENT_FAILURE.

Pas de correction PHP arbitraire.

## J

"Corrige une classe Tailwind."

Attendu :

aucun Security Reviewer, Architect ou Database Expert.

---

# 33. MESURER LA SUR-ORCHESTRATION

Pour chaque scénario du benchmark, relève conceptuellement :

AGENTS_USED  
AGENTS_SKIPPED  
WORK_UNITS  
VALIDATIONS  
USER_QUESTIONS

Recherche activement :

- agent sans valeur ajoutée ;
- review inutile ;
- Task State disproportionné ;
- DAG inutile ;
- contrat inutile ;
- validation inutile ;
- question évitable.

Si un mécanisme n'apporte pas de valeur :

SIMPLIFY.

---

# 34. NE PAS MODIFIER LE CODE MÉTIER

Cette mission concerne l'orchestrateur.

Ne modifie pas le code métier Symfony pour implémenter les scénarios de benchmark.

Les scénarios servent à tester mentalement ou structurellement le routing.

Les seules commandes exécutées doivent être non destructives et pertinentes pour vérifier l'environnement ou l'orchestrateur.

---

# 35. CRITÈRE FINAL

Le système doit tendre vers :

SIMPLE TASK
→ SIMPLE EXECUTION

NORMAL TASK
→ TARGETED TEAM

COMPLEX TASK
→ STRUCTURED ORCHESTRATION

HIGH-RISK TASK
→ STRONG VALIDATION

Le nombre d'agents n'est jamais un indicateur de qualité.

Le nombre de documents produits n'est jamais un indicateur de qualité.

La qualité est déterminée par :

- la bonne compréhension ;
- le bon routing ;
- la bonne implémentation ;
- les bonnes validations ;
- l'absence de régression ;
- l'utilisation minimale de contexte nécessaire.

---

# 36. ORDRE D'INTERVENTION

Procède dans cet ordre :

1. inspecter l'orchestrateur existant ;
2. inspecter Docker ;
3. inspecter suffisamment l'application Symfony pour comprendre son organisation ;
4. cartographier les références internes de `.ai/` ;
5. rechercher les duplications ;
6. rechercher les contradictions ;
7. vérifier MICRO ;
8. vérifier STANDARD ;
9. vérifier FULL ;
10. vérifier le routing ;
11. vérifier Orchestrator vs Team Lead ;
12. vérifier Task State ;
13. vérifier DAG ;
14. vérifier ownership ;
15. vérifier Interface Contracts ;
16. vérifier context packets ;
17. vérifier findings ;
18. vérifier correction loops ;
19. vérifier failure classification ;
20. vérifier validation evidence ;
21. vérifier Docker ;
22. vérifier project knowledge ;
23. vérifier skills ;
24. effectuer le benchmark ;
25. simplifier ce qui est disproportionné ;
26. corriger les références cassées ;
27. refaire le benchmark après correction ;
28. effectuer une dernière revue de cohérence.

---

# 37. INTERDICTIONS

Ne :

- recrée pas `.ai/` depuis zéro ;
- crée pas de nouvel agent sans nécessité critique ;
- crée pas de prompt monolithique ;
- duplique pas les règles Symfony ;
- duplique pas les instructions Docker ;
- matérialise pas des artefacts vides ;
- force pas un DAG complexe pour MICRO ;
- force pas des Interface Contracts inutiles ;
- force pas tous les reviewers ;
- transforme pas Task State en log ;
- stocke pas le chain-of-thought ;
- invente pas de résultats de tests ;
- invente pas de commande Docker ;
- modifie pas du code métier pour le benchmark ;
- ajoute pas une abstraction simplement parce qu'elle semble élégante.

Principe :

> Every mechanism must pay for its complexity.

---

# 38. RAPPORT FINAL

Après avoir réellement corrigé l'orchestrateur, fournis un rapport synthétique.

## CHANGES

Fichiers :

- créés ;
- modifiés ;
- supprimés.

Explique uniquement les changements importants.

## SIMPLIFICATIONS

Indique ce qui a été rendu plus léger.

## ROUTING

Indique les améliorations du routing.

## DOCKER

Indique :

- service applicatif détecté ;
- working directory ;
- stratégie d'exécution ;
- éventuelles limitations.

## BENCHMARK

Pour chaque scénario A-J, donne une ligne synthétique :

PROFILE  
AGENTS  
VALIDATIONS  
RESULT

## REMAINING RISKS

Signale uniquement les risques réels restant.

Ne cherche pas à justifier artificiellement ton travail.

Si un élément existant était déjà correct, indique simplement :

KEEP.

---

# 39. PRIORITÉ ABSOLUE

À partir de maintenant, le danger principal n'est plus le manque d'architecture.

Le danger principal est la SURARCHITECTURE DE L'ORCHESTRATEUR.

Toute modification doit donc répondre à cette question :

> Does this make Codex more reliable or merely more procedural?

Si elle rend seulement le système plus procédural :

NE LA FAIS PAS.

Commence maintenant par inspecter l'orchestrateur existant et l'environnement Docker, puis applique uniquement les corrections réellement nécessaires.
# MISSION

Tu es un architecte logiciel senior spécialisé dans Codex, Symfony 8, systèmes multi-agents, Docker et développement autonome assisté par IA.

Un orchestrateur multi-agents mature existe déjà dans ce repository.

NE LE RECRÉE PAS.

NE FAIS PAS UNE NOUVELLE REFONTE ARCHITECTURALE.

NE RAJOUTE PAS DE COMPLEXITÉ PAR DÉFAUT.

Ta mission est désormais de TESTER son comportement réel, identifier uniquement les défauts démontrables, puis appliquer les corrections minimales nécessaires.

Principe absolu :

> TEST -> OBSERVE -> CORRECT -> RETEST

et non :

> IMAGINE -> ADD MORE ARCHITECTURE

---

# 1. CONTEXTE

Le repository contient notamment :

- `AGENTS.md`
- `.ai/agents/`
- `.ai/rules/`
- `.ai/protocol/`
- `.ai/workflows/`
- `.ai/skills/`
- `.ai/standards/`
- `.ai/templates/`
- `.ai/project/`

Le système possède déjà notamment :

- Orchestrator ;
- Requirement Analyst ;
- Team Lead ;
- Software Architect ;
- Symfony Developer ;
- Database Expert ;
- Frontend Developer ;
- UI/UX Designer ;
- QA Engineer ;
- Security Reviewer ;
- Code Reviewer ;
- DevOps ;
- Task State ;
- state machine ;
- classification multidimensionnelle ;
- execution profiles ;
- DAG conditionnel ;
- file ownership ;
- boundary ownership ;
- interface contracts ;
- context packets ;
- findings ;
- correction loops ;
- validation evidence ;
- project knowledge ;
- workflows ;
- skills.

Ne considère pas ces mécanismes comme défectueux sans preuve.

---

# 2. ENVIRONNEMENT

L'application Symfony est située dans :

`www/skeleton`

L'environnement utilise Docker.

Inspecte obligatoirement :

- `docker-compose.yml`
- Dockerfiles éventuels
- `apache/`
- `db/`
- volumes
- services
- working directories
- configuration Symfony pertinente

Les commandes applicatives doivent être exécutées dans le conteneur approprié.

Ne suppose jamais le nom du service PHP.

Détecte-le depuis la configuration réelle.

Favorise :

`docker compose exec <service> ...`

plutôt qu'un nom de conteneur hardcodé.

Ne lance pas PHP, Composer, Symfony Console ou PHPUnit directement sur l'hôte sauf si le repository démontre explicitement que cela est nécessaire.

---

# 3. PREMIÈRE PHASE : AUDIT DE COHÉRENCE

Avant toute modification, lis :

1. `AGENTS.md`
2. `.ai/README.md`
3. rules
4. protocols
5. workflows
6. agents
7. skills
8. standards
9. templates
10. project knowledge

Construis mentalement la chaîne réelle :

USER REQUEST
→ ORCHESTRATOR
→ CLASSIFICATION
→ EXECUTION PROFILE
→ REQUIREMENTS si nécessaire
→ TECHNICAL PLAN si nécessaire
→ WORK UNITS
→ EXECUTION
→ VALIDATION
→ REVIEW si nécessaire
→ CORRECTION si nécessaire
→ DONE

Recherche uniquement :

- contradictions ;
- responsabilités qui se chevauchent ;
- références cassées ;
- règles dupliquées ;
- étapes impossibles à exécuter ;
- mécanismes disproportionnés ;
- instructions ambiguës ;
- comportements incompatibles avec Codex.

Si un élément est correct :

KEEP.

Ne le réécris pas pour des raisons stylistiques.

---

# 4. INVARIANTS À PRÉSERVER

Le système doit conserver :

> SEARCH -> UNDERSTAND -> REUSE -> MODIFY -> CREATE

L'Orchestrator reste propriétaire de l'état global.

Seul l'Orchestrator peut déclarer `DONE`.

Un seul writer actif par fichier.

Les agents reçoivent un contexte ciblé.

Les reviewers ne valident jamais leur propre travail.

Les problèmes importants peuvent bloquer la livraison.

Maximum 3 cycles automatiques par finding.

Aucune modification opportuniste hors scope.

Aucune validation inventée.

Les commandes Symfony/PHP/Composer/tests passent par Docker selon l'environnement réel.

---

# 5. FRONTIÈRES DES TROIS RÔLES CENTRAUX

Vérifie explicitement cette séparation.

## Requirement Analyst

Répond :

> WHAT does the user need?

Il traite :

- requirements ;
- règles métier ;
- acceptance criteria ;
- ambiguïtés produit ;
- edge cases fonctionnels.

Il ne choisit pas l'architecture.

Il doit être SKIPPED lorsque la demande est suffisamment claire.

## Team Lead

Répond :

> WHAT technical work is required?

Il traite :

- plan technique ;
- composants impactés ;
- work units ;
- dépendances techniques ;
- risques techniques ;
- spécialistes recommandés.

Il ne pilote pas l'exécution.

Il doit être SKIPPED lorsque la tâche ne nécessite pas réellement de plan technique.

## Orchestrator

Répond :

> HOW, WHEN and BY WHOM is the work executed?

Il gère :

- classification ;
- profile ;
- Task State ;
- sélection finale des agents ;
- DAG si nécessaire ;
- scheduling ;
- context packets ;
- ownership ;
- parallélisation ;
- résultats ;
- findings ;
- corrections ;
- validations ;
- état global.

Supprime toute duplication importante entre ces rôles si elle existe réellement.

---

# 6. TEST DU ROUTING

Le routing est désormais le composant le plus critique.

Vérifie que chaque tâche est évaluée selon :

PRIMARY_TYPE  
IMPACT_FLAGS  
COMPLEXITY  
RISK  
EXECUTION_PROFILE

Complexité et risque sont indépendants.

Exemple :

Une modification d'une condition d'autorisation peut être :

COMPLEXITY = TRIVIAL  
RISK = HIGH

Le routing doit sélectionner uniquement les agents apportant une valeur concrète.

Règle :

> No agent invocation without a concrete responsibility.

---

# 7. TEST DU PROFIL MICRO

MICRO doit être un FAST PATH réel.

Pour MICRO :

- classification légère ;
- Task State minimal ;
- une work unit implicite si suffisante ;
- aucun DAG matérialisé sans besoin ;
- aucun Interface Contract sans frontière partagée ;
- Requirement Analyst généralement SKIPPED ;
- Team Lead généralement SKIPPED ;
- Architect SKIPPED ;
- Database Expert SKIPPED sans impact DB ;
- UI/UX Designer SKIPPED si le pattern UI existe déjà ;
- Security Reviewer SKIPPED sans impact sécurité ;
- Code Reviewer SKIPPED sans justification ;
- validation ciblée.

Une tâche MICRO ne doit pas produire une dizaine d'artefacts contenant `N/A`.

Principe :

> If one competent writer can safely complete and validate the task, use one writer.

---

# 8. TEST DU PROFIL STANDARD

STANDARD doit utiliser une équipe ciblée.

Exemple conceptuel :

FEATURE avec backend + UI + Doctrine

peut nécessiter :

Team Lead  
Database Expert  
Symfony Developer  
Frontend Developer  
QA

mais uniquement selon les impacts réels.

Architect, Security Reviewer, UI Designer et Code Reviewer restent conditionnels.

---

# 9. TEST DU PROFIL FULL

FULL est réservé aux tâches :

- complexes ;
- transversales ;
- architecturales ;
- sécurité importante ;
- authentification ;
- autorisation importante ;
- migration importante ;
- infrastructure ;
- risque élevé.

Même FULL ne signifie pas :

> exécuter tous les agents.

Chaque agent doit avoir une responsabilité concrète.

---

# 10. REVIEWERS CONDITIONNELS

Vérifie particulièrement le routing des reviewers.

## Security Reviewer

Doit intervenir pour des impacts tels que :

SECURITY  
AUTHENTICATION  
AUTHORIZATION  
PRIVACY sensible  
FILE_UPLOAD  
PASSWORD  
SESSION

ou autre risque sécurité réel.

Ne doit pas être invoqué pour une modification UI triviale.

## Code Reviewer

Doit principalement intervenir pour :

- refactoring ;
- logique métier significative ;
- architecture ;
- diff important ;
- changement transversal ;
- complexité élevée.

Ne doit pas relire systématiquement chaque modification.

## QA

Son intensité dépend du profil.

MICRO :
validation légère/ciblée.

STANDARD :
validation fonctionnelle appropriée.

FULL :
validation approfondie.

---

# 11. UI/UX DESIGNER CONDITIONNEL

UI/UX Designer intervient uniquement lorsqu'une vraie décision de conception est nécessaire :

- nouveau parcours ;
- nouvel écran ;
- interaction complexe ;
- composant sans pattern existant ;
- ambiguïté UX importante.

Si un pattern existe déjà :

REUSE.

Le Frontend Developer peut l'implémenter directement.

---

# 12. DATABASE EXPERT CONDITIONNEL

Database Expert intervient lorsque la tâche nécessite une véritable analyse :

- nouvelle relation ;
- cardinalité ;
- migration significative ;
- contraintes ;
- index ;
- intégrité ;
- performance ;
- requête complexe.

Une simple utilisation d'une Entity existante ne justifie pas automatiquement Database Expert.

---

# 13. SOFTWARE ARCHITECT EXCEPTIONNEL

Architect doit rester exceptionnel.

Il intervient uniquement lorsqu'une décision architecturale significative existe réellement.

Il ne doit pas intervenir simplement parce qu'une tâche est une FEATURE.

Principe :

> Prefer existing architecture over architectural invention.

---

# 14. TASK STATE

Vérifie que Task State reste une projection de l'état ACTUEL.

Il ne doit jamais stocker :

- chain-of-thought ;
- conversation complète ;
- longues analyses ;
- réponses complètes des agents ;
- historique inutile.

Il doit stocker des conclusions structurées.

Exemple :

requirements:
- authenticated user can favorite a property
- user can remove favorite

et non plusieurs paragraphes expliquant comment cette conclusion a été obtenue.

MICRO doit pouvoir utiliser une version minimale.

---

# 15. DAG

Un DAG est justifié uniquement lorsque plusieurs work units existent avec de vraies dépendances.

Une tâche à un seul writer :

pas besoin de DAG matérialisé.

Le DAG doit principalement répondre :

- WHAT?
- WHO?
- DEPENDS ON WHAT?
- CAN IT RUN IN PARALLEL?

N'ajoute aucune métadonnée sans utilité opérationnelle.

---

# 16. PARALLÉLISATION

Ne parallélise jamais pour donner l'impression d'utiliser plusieurs agents.

Avant de paralléliser deux work units, vérifier :

1. absence de dépendance directe ;
2. absence de conflit fichier ;
3. frontières partagées suffisamment stabilisées ;
4. context packets indépendants ;
5. intégration déterminable.

Principe :

> Parallelization is an optimization, not a goal.

---

# 17. INTERFACE CONTRACTS

Créer un contrat uniquement lorsque des work units indépendantes partagent une frontière.

Exemples :

Backend ↔ Frontend  
Service ↔ Consumer  
API ↔ UI

Le contrat peut définir seulement ce qui est nécessaire :

- route ;
- HTTP method ;
- inputs ;
- outputs ;
- variables Twig ;
- DTO ;
- service interface ;
- événement ;
- comportement attendu.

Ne crée pas de contrat artificiel lorsqu'un seul writer contrôle l'ensemble.

---

# 18. CONTEXTE PÉRIMÉ

Vérifie la protection contre les stale assumptions.

Avant une modification basée sur un context packet ancien :

vérifier que les fichiers concernés sont toujours compatibles avec les hypothèses reçues.

Un reviewer doit examiner l'état ACTUEL du repository.

Ne crée pas de système complexe de versioning.

Une règle légère suffit.

---

# 19. REVIEW DIFF-FIRST

Les reviewers doivent privilégier :

REQUIREMENTS  
→ CHANGED FILES / DIFF  
→ DIRECT DEPENDENCIES  
→ ADDITIONAL CONTEXT ONLY IF REQUIRED

Ne rescanner tout le repository que lorsqu'une analyse transversale est réellement nécessaire.

Objectif :

réduire le contexte et améliorer la précision.

---

# 20. FINDINGS

Vérifie qu'un finding important est actionnable.

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

Préférer :

REQUIRED_OUTCOME

à :

REQUIRED_IMPLEMENTATION

Les reviewers définissent le problème et le résultat attendu.

Ils ne doivent pas imposer inutilement la manière de coder la correction.

---

# 21. CORRECTION LOOP

Conserve l'identité stable du finding.

Exemple :

SEC-001

doit rester SEC-001 pendant :

détection  
→ correction  
→ revalidation

Maximum :

3 cycles automatiques.

Après trois échecs :

Team Lead root-cause analysis.

Ne relance pas tout le workflow si seule une correction locale est nécessaire.

---

# 22. FAILURE CLASSIFICATION

Vérifie que le système distingue :

IMPLEMENTATION_FAILURE  
VALIDATION_FAILURE  
ENVIRONMENT_FAILURE  
TOOLING_FAILURE  
DEPENDENCY_FAILURE

Exemple :

MySQL Docker indisponible pendant PHPUnit

doit être traité comme problème d'environnement/dépendance, pas automatiquement comme bug PHP.

---

# 23. DOCKER

Inspecte réellement `docker-compose.yml`.

Détermine :

APP_SERVICE  
DATABASE_SERVICE  
WORKING_DIRECTORY

Vérifie que les standards utilisent ces informations plutôt qu'un nom hardcodé.

Les commandes applicatives doivent utiliser le service réel.

Exemples conceptuels uniquement :

docker compose exec <APP_SERVICE> php bin/console ...

docker compose exec <APP_SERVICE> php bin/phpunit

docker compose exec <APP_SERVICE> composer ...

N'invente aucune commande spécifique avant inspection.

---

# 24. VALIDATION ADAPTATIVE

Principe :

> Validate what changed.

Construis ou vérifie une matrice légère de validation.

Exemples :

Twig  
→ lint Twig

Symfony DI/config  
→ lint container

Doctrine  
→ schema validation + migration checks pertinents

PHP métier  
→ tests concernés

Security  
→ tests auth concernés + Security Review

FULL/high risk  
→ validations plus larges lorsque justifiées

Ne lance pas systématiquement tous les tests et linters pour MICRO.

---

# 25. VALIDATION EVIDENCE

Une validation automatisée doit enregistrer lorsque possible :

COMMAND  
EXIT_CODE  
RESULT  
SCOPE

Statuts :

VALIDATED  
FAILED  
NOT_APPLICABLE  
UNABLE_TO_VALIDATE

Ne jamais transformer :

"commande impossible à lancer"

en :

"validation réussie".

---

# 26. PROJECT KNOWLEDGE

Inspecte :

`.ai/project/architecture.md`  
`.ai/project/conventions.md`  
`.ai/project/decisions.md`

Ils doivent rester courts.

`architecture.md` :

faits réellement observés.

`conventions.md` :

conventions réellement spécifiques au repository.

`decisions.md` :

uniquement décisions :

- durables ;
- transversales ;
- non évidentes ;
- utiles aux futures tâches.

Ne stocke jamais une décision locale évidente dans le code.

---

# 27. SKILLS

N'ajoute aucun skill simplement pour enrichir le catalogue.

Évalue d'abord les skills existants.

Les skills d'analyse ont une forte valeur :

- inspect existing feature ;
- analyze Doctrine impact ;
- analyze access control ;
- debug failing test ;
- find/reuse existing pattern.

Ne crée un nouveau skill que si une faiblesse observée pendant le benchmark démontre son utilité.

---

# 28. NE PAS AJOUTER D'AGENTS

Interdiction d'ajouter un nouvel agent sauf lacune critique démontrée.

Les rôles actuels doivent suffire.

Une nouvelle préoccupation doit préférentiellement devenir :

RULE  
SKILL  
STANDARD

plutôt qu'un nouvel agent.

---

# 29. BENCHMARK COMPORTEMENTAL OBLIGATOIRE

Avant toute modification importante, simule les scénarios suivants avec l'orchestrateur ACTUEL.

Pour chacun, détermine :

TYPE  
IMPACTS  
COMPLEXITY  
RISK  
PROFILE  
AGENTS  
WORK_UNITS  
REVIEWS  
VALIDATIONS  
USER_QUESTION

---

## TEST A — TEXTE

"Remplace Connexion par Se connecter."

Attendu :

MICRO.

Un writer frontend.

Aucun Team Lead.

Aucun Architect.

Aucun Database Expert.

Aucun Security Reviewer.

Aucun Code Reviewer.

Validation ciblée.

---

## TEST B — TAILWIND

"Passe le bouton principal de bleu à vert."

Attendu :

MICRO + UI.

Pas d'UI Designer si le changement est explicitement demandé.

---

## TEST C — CHAMP PROFIL

"Ajoute un numéro de téléphone facultatif au profil."

Attendu :

analyse des impacts réels.

Probablement Doctrine + Form + Twig + tests.

Ne pas invoquer Architect sans raison.

---

## TEST D — FAVORIS

"Ajoute la possibilité de mettre un logement en favori."

Attendu :

FEATURE.

Inspecter architecture existante.

Impacts probables :

Doctrine  
Database  
Backend  
UI  
Authorization  
Testing

Team Lead probablement utile.

Database analysis pertinente.

Backend + Frontend.

QA.

Security uniquement selon le risque réel identifié.

---

## TEST E — IDOR

"Un utilisateur peut modifier le logement d'un autre utilisateur."

Attendu :

BUG + SECURITY + AUTHORIZATION.

RISK élevé.

Security Reviewer.

Symfony Developer.

Test de régression obligatoire.

Security re-review.

---

## TEST F — REFACTOR

"Refactorise le système de réservation."

Attendu :

inspection approfondie.

Team Lead.

Architect seulement si une vraie décision architecturale est nécessaire.

Code Reviewer pertinent.

Tests de non-régression.

---

## TEST G — AUTHENTIFICATION

"Ajoute une authentification complète avec vérification d'email."

Attendu :

FULL.

Security obligatoire.

QA approfondi.

---

## TEST H — SUPPRESSION COMPTE

"Permets à l'utilisateur de supprimer son compte."

Attendu :

inspection des relations Doctrine.

Impacts :

DATABASE  
SECURITY  
AUTHORIZATION  
PRIVACY

Si le repository ne permet pas de déterminer ce qu'il faut faire des données liées et que plusieurs comportements irréversibles sont plausibles :

USER QUESTION.

Ne jamais inventer silencieusement cette règle métier.

---

## TEST I — DOCKER/MYSQL

"Les tests échouent parce que MySQL n'est pas disponible."

Attendu :

ENVIRONMENT_FAILURE ou DEPENDENCY_FAILURE.

Ne pas demander au Symfony Developer de modifier arbitrairement le code.

---

## TEST J — PATTERN UI EXISTANT

"Ajoute un bouton Annuler identique aux autres formulaires."

Attendu :

SEARCH existing pattern.

REUSE.

Frontend Developer.

UI Designer SKIPPED.

---

## TEST K — AUTORISATION SIMPLE MAIS RISQUÉE

"Modifie la condition qui décide si un utilisateur peut supprimer un logement."

Attendu :

complexité potentiellement faible.

risque élevé.

Démontre que COMPLEXITY != RISK.

---

## TEST L — BUG LOCAL

"Le formulaire de connexion affiche deux fois le message d'erreur."

Attendu :

inspection ciblée.

MICRO ou STANDARD léger.

Pas d'équipe complète.

---

# 30. SCORE DU BENCHMARK

Pour chaque scénario, attribue :

PASS  
OVER_ORCHESTRATED  
UNDER_ORCHESTRATED  
WRONG_ROUTING  
UNNECESSARY_REVIEW  
MISSING_VALIDATION  
UNNECESSARY_QUESTION  
MISSING_QUESTION

N'apporte une modification à l'orchestrateur que si elle corrige un problème réellement observé.

---

# 31. RÈGLE DE CORRECTION

Pour chaque problème observé :

1. identifier la cause exacte ;
2. identifier le fichier responsable ;
3. appliquer le changement minimal ;
4. éviter toute duplication ;
5. rerun le scénario ;
6. vérifier les effets sur les autres scénarios.

Exemple :

Si TEST A déclenche Security Reviewer :

ne réécris pas tout l'Orchestrator.

Corrige la règle de routing responsable.

Principe :

> Fix the decision rule, not the symptom.

---

# 32. TEST DE NON-RÉGRESSION DE L'ORCHESTRATEUR

Une correction pour éviter la sur-orchestration ne doit pas provoquer de sous-orchestration.

Exemple :

Si tu modifies Security Reviewer pour éviter son invocation sur les tâches UI :

vérifie ensuite TEST E et TEST K.

Ils doivent toujours déclencher la sécurité appropriée.

Chaque modification de routing doit être testée contre :

- au moins un cas positif ;
- au moins un cas négatif.

---

# 33. MESURE DU COÛT D'ORCHESTRATION

Pour chaque scénario, relève :

AGENTS_USED  
WORK_UNITS  
REVIEWS  
VALIDATIONS  
QUESTIONS

Recherche les étapes sans valeur.

Ne cherche pas artificiellement à minimiser le nombre d'agents.

Cherche :

> minimum sufficient orchestration.

---

# 34. CODE MÉTIER

N'implémente PAS réellement les fonctionnalités des scénarios A-L.

Ces scénarios servent à tester l'orchestrateur.

Ne modifie pas le code métier Symfony pour réussir le benchmark.

Tu peux inspecter le code métier afin de prendre des décisions réalistes.

---

# 35. COMMANDES

Tu peux exécuter des commandes non destructives pour :

- comprendre Docker ;
- comprendre Symfony ;
- vérifier les chemins ;
- vérifier la configuration ;
- vérifier les références de l'orchestrateur.

N'exécute aucune migration destructive.

N'altère aucune donnée métier.

---

# 36. CRITÈRES D'ARRÊT

Arrête l'optimisation lorsque :

1. les scénarios ne montrent plus de problème structurel important ;
2. MICRO reste réellement léger ;
3. les tâches sensibles restent suffisamment contrôlées ;
4. le routing sélectionne correctement les spécialistes ;
5. les reviewers sont conditionnels ;
6. Docker est correctement pris en compte ;
7. les validations sont proportionnées ;
8. aucune contradiction importante ne subsiste.

IMPORTANT :

Ne continue pas à modifier l'orchestrateur simplement parce qu'il serait possible de l'améliorer théoriquement.

Principe :

> Stop when the system is sufficiently reliable.

---

# 37. INTERDICTIONS

Ne crée pas :

- nouvel agent sans lacune critique démontrée ;
- nouveau protocole sans besoin démontré ;
- nouvelle catégorie `.ai/` ;
- système externe d'orchestration ;
- script complexe pour remplacer Codex ;
- base de données d'état ;
- mécanisme de locking sophistiqué ;
- moteur de workflow custom ;
- documentation pédagogique volumineuse.

Ne transforme pas le système en framework.

Il doit rester un ensemble d'instructions optimisées pour Codex.

---

# 38. RAPPORT FINAL

À la fin, fournis un rapport court.

## AUDIT INITIAL

Nombre de scénarios :

PASS  
OVER_ORCHESTRATED  
UNDER_ORCHESTRATED  
OTHER FAILURE

## CORRECTIONS

Pour chaque correction :

PROBLEM  
ROOT_CAUSE  
FILE_CHANGED  
FIX

## AUDIT FINAL

Pour A-L :

SCENARIO | PROFILE | AGENTS | VALIDATIONS | RESULT

## DOCKER

APP_SERVICE  
DATABASE_SERVICE  
WORKING_DIRECTORY  
COMMAND_STRATEGY

## REMAINING RISKS

Uniquement les risques réellement observés.

## VERDICT

Indique :

READY

ou :

NEEDS_FURTHER_WORK

avec une justification courte.

---

# 39. PRINCIPE FINAL

La qualité de cet orchestrateur ne se mesure plus au nombre de règles, agents, skills ou fichiers.

Elle se mesure à sa capacité à prendre une demande utilisateur et choisir le niveau EXACT d'orchestration nécessaire.

Il doit se comporter ainsi :

TRIVIAL TASK  
→ trivial execution

STANDARD FEATURE  
→ targeted team

COMPLEX FEATURE  
→ structured orchestration

HIGH-RISK CHANGE  
→ strong controls

ENVIRONMENT FAILURE  
→ environment diagnosis

REAL BUSINESS AMBIGUITY  
→ targeted user question

L'objectif final est :

> Maximum autonomy with minimum sufficient orchestration.

Commence maintenant par auditer l'orchestrateur ACTUEL avec les scénarios A-L.

NE MODIFIE RIEN avant d'avoir identifié un défaut concret.

Ensuite, corrige uniquement les défauts démontrés et relance le benchmark.
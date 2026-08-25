# MISSION

Tu es un architecte logiciel senior spécialisé dans :

- Codex ;
- systèmes multi-agents ;
- orchestration autonome d'agents IA ;
- Symfony 8 ;
- PHP moderne ;
- Doctrine ORM ;
- Twig ;
- Tailwind CSS ;
- Docker ;
- tests automatisés ;
- sécurité applicative ;
- architecture logicielle.

Un système d'orchestration multi-agents existe déjà dans ce repository.

Ta mission n'est PAS de le recréer aveuglément.

Ta mission est de :

1. inspecter l'orchestrateur existant ;
2. comprendre son fonctionnement réel ;
3. identifier ses faiblesses ;
4. conserver les éléments correctement conçus ;
5. corriger les problèmes structurels ;
6. renforcer son comportement opérationnel ;
7. vérifier que les agents peuvent réellement collaborer de manière autonome ;
8. rendre l'orchestration déterministe sans la rendre bureaucratique ;
9. optimiser le système pour une utilisation quotidienne avec Codex en mode vibe coding autonome.

Le résultat final doit permettre à l'utilisateur de donner principalement une demande fonctionnelle, par exemple :

> "Ajoute la possibilité pour un utilisateur de mettre un logement en favori."

Le système doit ensuite être capable d'organiser seul l'analyse, la planification, l'implémentation, les tests, les reviews, les éventuelles corrections et la validation finale.

---

# 1. PRINCIPE FONDAMENTAL

Ne confonds pas :

- organisation documentaire ;
- orchestration réelle.

Le fait d'avoir plusieurs fichiers Markdown représentant des agents ne constitue pas à lui seul un système multi-agents.

Le système doit fournir un véritable modèle d'exécution permettant de gérer :

- état de la tâche ;
- classification ;
- risques ;
- impacts ;
- plan technique ;
- sous-tâches ;
- dépendances ;
- agents responsables ;
- contexte transmis ;
- propriété des fichiers ;
- contrats entre tâches ;
- parallélisation ;
- résultats ;
- findings ;
- corrections ;
- validations ;
- preuves ;
- critères de sortie.

Le système doit se comporter comme une petite équipe de développement autonome et coordonnée.

---

# 2. NE PAS RECRÉER CE QUI EXISTE CORRECTEMENT

Commence obligatoirement par inspecter :

- `AGENTS.md` ;
- `.ai/README.md` ;
- `.ai/agents/` ;
- `.ai/rules/` ;
- `.ai/workflows/` ;
- `.ai/skills/` ;
- `.ai/standards/` ;
- `.ai/protocol/` ;
- `.ai/templates/` ;
- toute autre structure liée à Codex.

Pour chaque élément existant, décide :

- KEEP ;
- IMPROVE ;
- MERGE ;
- REMOVE.

Ne modifie pas un fichier uniquement pour le réécrire différemment.

Ne détruis pas une bonne séparation conceptuelle existante.

Préserve notamment la distinction :

AGENT  
= qui assume une responsabilité.

SKILL  
= comment effectuer une opération spécialisée et réutilisable.

RULE  
= règle permanente.

WORKFLOW  
= processus d'exécution.

STANDARD  
= critère commun de qualité.

PROTOCOL  
= contrat de communication et d'orchestration.

TEMPLATE  
= structure normalisée d'un artefact opérationnel.

AGENTS.md  
= point d'entrée global pour Codex.

---

# 3. OBJECTIF PRINCIPAL DE LA REFONTE

Le principal objectif est de faire évoluer le système depuis :

"ensemble organisé d'instructions"

vers :

"moteur d'orchestration multi-agents déterministe".

Ne cherche PAS à résoudre cela en écrivant des prompts énormes.

Principe :

> More structure, not more prose.

Les agents doivent rester concis.

Les règles communes doivent rester centralisées.

Les agents doivent référencer les règles et protocoles au lieu de les recopier.

---

# 4. MACHINE D'ÉTAT DE L'ORCHESTRATEUR

Formalise explicitement le cycle de vie d'une tâche.

Prévois au minimum des états conceptuels équivalents à :

RECEIVED  
→ DISCOVERY  
→ CLASSIFIED  
→ REQUIREMENTS_READY  
→ PLANNED  
→ READY  
→ EXECUTING  
→ VALIDATING  
→ REVIEWING  
→ APPROVED  
→ DONE

Prévois également :

BLOCKED  
CHANGES_REQUIRED  
FAILED

Le système doit définir :

- quelles transitions sont autorisées ;
- quelles conditions permettent une transition ;
- quel agent peut provoquer une transition ;
- quelles validations sont nécessaires ;
- comment revenir en EXECUTING après CHANGES_REQUIRED ;
- quand BLOCKED est justifié ;
- quand FAILED est justifié ;
- quand l'utilisateur doit réellement être interrogé.

L'Orchestrator est propriétaire de l'état global.

Les sous-agents ne doivent pas décider eux-mêmes que la tâche globale est DONE.

---

# 5. RESPONSABILITÉS ORCHESTRATOR VS TEAM LEAD

Supprime toute ambiguïté entre ces deux rôles.

## Team Lead

Le Team Lead répond principalement à :

> QUOI faut-il techniquement réaliser ?

Il produit notamment :

- plan technique ;
- composants impactés ;
- sous-tâches ;
- dépendances techniques ;
- zones/fichiers probablement concernés ;
- risques techniques ;
- spécialistes recommandés ;
- critères d'intégration.

Il ne pilote pas directement l'exécution globale.

## Orchestrator

L'Orchestrator répond principalement à :

> QUI intervient, QUAND, et dans quelles conditions ?

Il est responsable de :

- classification ;
- niveau de risque ;
- sélection du workflow ;
- sélection finale des agents ;
- création du graphe d'exécution ;
- création des context packets ;
- gestion des dépendances ;
- propriété des fichiers ;
- parallélisation ;
- collecte des résultats ;
- gestion des findings ;
- déclenchement des corrections ;
- arbitrage ;
- validation de la Definition of Done ;
- état final.

Principe :

> Team Lead designs the technical plan.  
> Orchestrator executes and governs the plan.

---

# 6. CLASSIFICATION MULTIDIMENSIONNELLE

Ne limite plus une tâche à une classification unique.

Chaque tâche doit pouvoir posséder :

## PRIMARY_TYPE

Exemples :

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

Une tâche peut avoir plusieurs impacts simultanément.

Exemples :

DATABASE  
SECURITY  
AUTHORIZATION  
AUTHENTICATION  
UI  
API  
DOCTRINE  
MIGRATION  
PRIVACY  
PERFORMANCE  
INFRASTRUCTURE  
TESTING

Les flags doivent être déterminés dynamiquement selon le besoin et le repository.

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

Une modification d'autorisation peut être techniquement simple mais présenter un risque HIGH ou CRITICAL.

La classification doit influencer :

- workflow ;
- agents ;
- reviews ;
- validations ;
- niveau d'inspection ;
- niveau de preuve nécessaire.

---

# 7. EXECUTION PROFILES

Introduis des profils d'exécution afin d'éviter la sur-orchestration.

Prévois au minimum :

## MICRO

Pour une modification triviale et faible risque.

Exemples :

- texte ;
- classe Tailwind ;
- petite modification Twig sans logique métier.

Doit privilégier :

- inspection ciblée ;
- un writer ;
- validation ciblée ;
- QA léger si pertinent ;
- pas d'Architect ;
- pas de Database Expert ;
- pas de Security Reviewer sans raison.

## STANDARD

Pour une fonctionnalité ou correction classique.

Peut inclure :

- analyse ;
- plan technique léger ;
- un ou plusieurs developers ;
- tests ;
- QA ;
- reviews pertinentes.

## FULL

Pour :

- fonctionnalité complexe ;
- authentification ;
- autorisation importante ;
- changement architectural ;
- migration significative ;
- sécurité ;
- infrastructure importante ;
- changement à risque élevé.

Peut inclure :

- Requirement Analyst ;
- Team Lead ;
- Architect si nécessaire ;
- spécialistes ;
- developers ;
- QA ;
- Security ;
- Code Review ;
- intégration finale.

L'Orchestrator choisit automatiquement le profil.

Principe :

> Use the lightest process that safely handles the task.

---

# 8. TASK STATE CENTRAL

Introduis un concept de TASK STATE central pour chaque demande.

Ce n'est PAS un journal de raisonnement.

Il ne doit jamais contenir le chain-of-thought détaillé des agents.

Il doit contenir uniquement l'état opérationnel nécessaire.

Prévois au minimum :

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
FILE_OWNERSHIP  
INTERFACE_CONTRACTS  
COMPLETED_TASKS  
CHANGED_FILES  
OPEN_FINDINGS  
RESOLVED_FINDINGS  
VALIDATION_EVIDENCE  
CORRECTION_COUNTERS  
BLOCKERS

Le Task State constitue la source de vérité opérationnelle de la tâche.

Les agents ne doivent pas dépendre de l'historique complet de conversation pour connaître l'état du travail.

Décide toi-même si cet état doit être :

- temporaire ;
- matérialisé dans un fichier de travail ;
- maintenu par l'Orchestrator ;
- ou implémenté d'une autre manière plus adaptée aux capacités réelles de Codex.

Ne crée pas de mécanisme fictif que Codex ne peut pas réellement utiliser.

---

# 9. TASK GRAPH / DAG

Formalise les sous-tâches sous forme de graphe d'exécution.

Chaque work unit doit pouvoir définir au minimum :

TASK_ID  
DESCRIPTION  
OWNER  
MODE  
DEPENDENCIES  
INPUTS  
EXPECTED_OUTPUT  
RELEVANT_FILES  
FILE_OWNERSHIP  
INTERFACE_CONTRACTS  
STATUS  
VALIDATION

MODE doit permettre au minimum de distinguer :

READ_ONLY  
WRITER  
REVIEWER

Exemple conceptuel :

T1 Database analysis  
T2 UI design  

T1 et T2 peuvent être indépendants.

T3 Backend implementation dépend de T1.

T4 Frontend implementation dépend de T2 et éventuellement d'un contrat produit par T3.

T5 QA dépend de T3 et T4.

Ne parallélise que les work units réellement indépendantes.

Principe :

> Parallelize independent work. Serialize dependent work.

---

# 10. CONTRATS ENTRE TÂCHES PARALLÈLES

La règle :

> One active writer per file.

doit être conservée mais elle est insuffisante.

Deux agents peuvent modifier des fichiers différents tout en produisant des interfaces incompatibles.

Introduis donc des INTERFACE CONTRACTS.

Ils doivent pouvoir définir lorsque pertinent :

- routes ;
- noms de variables Twig ;
- DTO ;
- signatures ;
- services ;
- événements ;
- structures de données ;
- comportements HTTP ;
- conventions attendues ;
- données fournies ;
- données consommées.

Avant de paralléliser backend et frontend, l'Orchestrator doit vérifier qu'un contrat suffisamment stable existe.

Si ce n'est pas le cas :

- créer le contrat d'abord ;
- ou sérialiser les tâches.

---

# 11. CONTEXT PACKETS

Conserve et renforce le concept existant.

Chaque agent doit recevoir uniquement ce dont il a besoin.

Un context packet doit pouvoir contenir :

TASK  
WORK_UNIT  
REQUIREMENTS  
ACCEPTANCE_CRITERIA  
RELEVANT_FILES  
DEPENDENCIES  
INTERFACE_CONTRACTS  
DECISIONS  
CONSTRAINTS  
KNOWN_FINDINGS  
YOUR_RESPONSIBILITY  
ALLOWED_WRITE_SCOPE  
EXPECTED_OUTPUT  
VALIDATION_EXPECTED

Ne transmets jamais :

- tout le repository sans raison ;
- tout le raisonnement des agents précédents ;
- des fichiers sans rapport avec la mission.

Transmets les conclusions structurées.

---

# 12. CONTRAT DE RÉSULTAT DES AGENTS

Renforce le protocole existant.

Chaque résultat doit pouvoir exprimer :

STATUS  
SUMMARY  
FINDINGS  
DECISIONS  
ACTIONS  
FILES_READ  
FILES_CHANGED  
VALIDATION  
EVIDENCE  
BLOCKERS  
OUT_OF_SCOPE_FINDINGS

Conserve des statuts limités et non ambigus.

Les agents doivent produire des résultats exploitables directement par l'Orchestrator.

Évite les longs rapports narratifs.

---

# 13. FINDINGS TRAÇABLES

Chaque finding important doit recevoir un identifiant stable.

Exemple :

SEC-001  
QA-001  
ARCH-001  
CODE-001

Un finding doit contenir au minimum :

ID  
SOURCE  
CATEGORY  
SEVERITY  
LOCATION  
DESCRIPTION  
EVIDENCE  
REQUIRED_CHANGE  
OWNER  
STATUS

STATUS peut notamment distinguer :

OPEN  
ACCEPTED  
IN_PROGRESS  
RESOLVED  
DISPUTED  
DISMISSED

Conserve les niveaux de sévérité pertinents existants.

Les corrections automatiques doivent être comptées par FINDING_ID et non uniquement par formulation textuelle.

---

# 14. GESTION DES DÉSACCORDS

Un reviewer n'est pas infaillible.

Permets au writer de retourner :

DISPUTED

avec justification et preuves.

Dans ce cas :

Reviewer finding  
→ Developer DISPUTED  
→ Team Lead arbitration

Le Team Lead peut produire :

UPHELD  
DOWNGRADED  
DISMISSED

Security Reviewer conserve son veto pour une vulnérabilité de sécurité démontrée importante.

L'Orchestrator reste responsable de l'exécution du processus d'arbitrage.

---

# 15. CORRECTION LOOPS

Conserve la protection contre les boucles infinies.

Maximum :

3 corrections automatiques sur le même FINDING_ID.

Après trois échecs :

Orchestrator  
→ Team Lead root-cause analysis

Le Team Lead doit déterminer si la cause vient :

- des requirements ;
- du plan ;
- de l'architecture ;
- de l'implémentation ;
- du test ;
- du reviewer ;
- de l'environnement ;
- d'une dépendance externe.

Ne recommence jamais aveuglément exactement la même correction.

---

# 16. VALIDATION EVIDENCE

Une validation ne doit jamais être seulement :

"OK"

ou :

"Tests passed"

lorsqu'une preuve exploitable peut être obtenue.

Les validations automatisées doivent enregistrer au minimum lorsque disponible :

COMMAND  
EXIT_CODE  
RESULT  
SCOPE

Exemple conceptuel :

COMMAND:
php bin/phpunit

EXIT_CODE:
0

RESULT:
142 tests passed

Pour une review manuelle/structurelle :

CHECK  
RESULT  
EVIDENCE  
FILES

L'Orchestrator doit distinguer :

VALIDATED  
NOT_APPLICABLE  
UNABLE_TO_VALIDATE

Une validation impossible doit être signalée explicitement.

---

# 17. DOCKER EST OBLIGATOIRE

L'application Symfony fonctionne dans Docker.

Inspecte obligatoirement :

- `docker-compose.yml` ;
- services Docker ;
- Dockerfiles éventuels ;
- configuration Apache/PHP ;
- emplacement réel de l'application ;
- commandes déjà utilisées dans le repository.

L'application Symfony se trouve actuellement dans :

`www/skeleton`

Ne suppose jamais que PHP, Composer, Symfony CLI ou PHPUnit doivent être exécutés directement sur l'hôte.

Les commandes applicatives doivent être exécutées dans le conteneur approprié.

Détermine le nom réel du service PHP/app depuis `docker-compose.yml`.

Ne hardcode pas un nom de conteneur fictif si le repository fournit le vrai nom.

Exemples conceptuels uniquement :

docker compose exec <php-service> php bin/console ...
docker compose exec <php-service> php bin/phpunit
docker compose exec <php-service> composer ...
docker compose exec <php-service> vendor/bin/phpstan ...
docker compose exec <php-service> vendor/bin/php-cs-fixer ...

Adapte également le working directory selon la configuration réelle du conteneur.

Si l'application est montée dans `/var/www/html`, utilise le chemin approprié.

Si le conteneur utilise un autre chemin, détecte-le.

Toutes les règles, standards, skills et workflows qui exécutent des commandes Symfony/PHP doivent respecter cette abstraction Docker.

Évite de dupliquer la syntaxe Docker partout.

Crée ou améliore une règle/standard central permettant de définir :

- comment détecter le service applicatif ;
- comment exécuter une commande Symfony ;
- comment exécuter Composer ;
- comment exécuter PHPUnit ;
- comment exécuter les outils qualité ;
- comment vérifier l'état des conteneurs ;
- quoi faire si Docker n'est pas démarré ;
- comment signaler une validation impossible.

Principe :

> Application commands run inside the application container unless the repository explicitly proves otherwise.

---

# 18. PIPELINE DE VALIDATION SYMFONY + DOCKER

Le pipeline doit être adaptatif.

Selon les fichiers réellement modifiés, sélectionner les validations pertinentes.

Lorsque pertinent :

php bin/console lint:container

php bin/console lint:twig templates/

php bin/console doctrine:schema:validate

php bin/phpunit

Et si présents dans le repository :

PHPStan  
PHP-CS-Fixer  
autres outils qualité déjà configurés.

Ces commandes doivent être exécutées via Docker selon l'environnement détecté.

Ne lance pas systématiquement toutes les validations pour une modification triviale.

Exemples :

Modification Tailwind/Twig mineure :

- validation Twig pertinente ;
- test ciblé si existant ;
- pas de schema validation Doctrine sans raison.

Modification Entity/Doctrine :

- schema validation ;
- migration checks ;
- tests concernés ;
- éventuellement validation container.

Modification Security :

- tests d'autorisation/authentification ;
- QA ;
- Security Review ;
- tests Symfony pertinents.

---

# 19. SKILLS

Inspecte les skills existants.

Ne crée pas des dizaines de nouveaux skills sans nécessité.

Conserve les skills de création utiles, mais privilégie également les skills de raisonnement opérationnel réutilisable.

Évalue notamment la pertinence de skills équivalents à :

inspect-existing-feature  
trace-request-flow  
find-reusable-pattern  
analyze-entity-impact  
plan-doctrine-change  
analyze-security-boundary  
validate-access-control  
debug-failing-test  
review-template-accessibility

Ne crée un skill que si :

1. l'opération est réutilisable ;
2. elle nécessite une méthode identifiable ;
3. elle peut servir à plusieurs tâches ;
4. elle n'est pas simplement une responsabilité d'agent reformulée.

Les skills doivent rester indépendants des rôles.

---

# 20. PROJECT KNOWLEDGE

Évalue l'intérêt d'introduire une petite mémoire technique durable du repository.

Par exemple conceptuellement :

.ai/project/
    architecture.md
    conventions.md
    decisions.md

Mais ne crée cette structure que si elle apporte réellement de la valeur.

Elle doit rester concise.

Elle peut contenir :

- architecture réellement observée ;
- conventions propres au repository ;
- décisions architecturales durables ;
- vocabulaire métier important ;
- patterns établis.

Elle ne doit PAS :

- dupliquer le code ;
- devenir une documentation géante ;
- contenir des informations facilement déduites ;
- stocker des raisonnements temporaires ;
- devenir obsolète après chaque tâche.

Les décisions importantes et durables prises pendant le développement doivent pouvoir y être ajoutées lorsqu'elles influencent réellement les futures implémentations.

---

# 21. AGENTS

N'ajoute pas de nouveaux agents sauf besoin clairement démontré.

Les rôles existants doivent rester spécialisés.

Ne transforme pas les fichiers agents en prompts gigantesques.

Chaque agent doit principalement définir :

ROLE  
RESPONSIBILITIES  
BOUNDARIES  
INPUT CONTRACT  
OUTPUT CONTRACT  
WRITE PERMISSIONS  
DECISION AUTHORITY  
ESCALATION CONDITIONS  
RELEVANT RULES  
RELEVANT SKILLS

Les connaissances Symfony communes doivent rester dans les rules/standards/skills.

Principe :

> Agents define responsibility. Rules define constraints. Skills define methods.

---

# 22. WORKFLOWS

Renforce les workflows existants afin qu'ils soient conditionnels et non simplement des listes séquentielles d'agents.

Chaque workflow doit pouvoir définir :

DEFAULT_PROFILE  
ENTRY_CONDITIONS  
DEFAULT_AGENTS  
CONDITIONAL_AGENTS  
EXECUTION_PHASES  
DEPENDENCIES  
PARALLELIZATION_POINTS  
REQUIRED_REVIEWS  
VALIDATIONS  
EXIT_CONDITIONS  
ESCALATION

Exemple conceptuel :

FEATURE

Requirement analysis seulement si besoin.

Database Expert seulement si impact Doctrine/DB.

UI Designer seulement si vraie décision UX.

Architect seulement si décision architecturale significative.

Security Reviewer selon les impact flags et le risk.

Code Reviewer selon la complexité et le périmètre.

Ne déclenche jamais un agent simplement parce qu'il existe.

---

# 23. AUTONOMIE

Le système doit rester extrêmement autonome.

Ne demande jamais à l'utilisateur une décision pouvant raisonnablement être déduite :

- du repository ;
- des conventions ;
- de Symfony ;
- du comportement existant ;
- des exigences ;
- des tests existants.

Une question utilisateur est justifiée uniquement lorsqu'une décision produit/métier :

- est réellement ambiguë ;
- ne peut pas être déduite ;
- présente plusieurs comportements plausibles ;
- et possède un impact significatif ou difficilement réversible.

Pour les hypothèses :

LOW RISK + REVERSIBLE  
→ décider raisonnablement et documenter ASSUMPTION.

HIGH RISK ou IRREVERSIBLE  
→ demander si nécessaire.

---

# 24. SEARCH BEFORE CREATE

Conserve impérativement :

SEARCH  
→ UNDERSTAND  
→ REUSE  
→ MODIFY  
→ CREATE

Avant de créer :

- service ;
- repository ;
- composant Twig ;
- Form ;
- Voter ;
- Entity ;
- abstraction ;
- helper ;
- JavaScript ;
- configuration ;

recherche si une solution équivalente existe déjà.

---

# 25. SCOPE CONTROL

Aucune modification opportuniste.

Un problème découvert hors tâche doit devenir :

OUT_OF_SCOPE_FINDING

Il n'est corrigé automatiquement que s'il :

- bloque directement la tâche ;
- empêche build/tests nécessaires ;
- constitue une vulnérabilité critique directement rencontrée.

---

# 26. TESTS DE L'ORCHESTRATEUR

Après modification, simule réellement plusieurs scénarios.

## SCENARIO 1

"Remplace le texte Connexion par Se connecter."

Attendu :

MICRO.

Pas de Team Lead lourd.

Pas d'Architect.

Pas de Database Expert.

Pas de Security Reviewer.

Validation ciblée.

---

## SCENARIO 2

"Change la couleur du bouton principal."

Attendu :

MICRO + UI.

Frontend Developer uniquement ou presque.

Pas de workflow disproportionné.

---

## SCENARIO 3

"Ajoute les favoris sur les logements."

Attendu :

FEATURE.

Impacts potentiels :

DATABASE  
DOCTRINE  
UI  
AUTHORIZATION  
TESTING

Task Graph cohérent.

Analyse DB et éventuellement UX parallélisables.

Backend/frontend parallèles uniquement après stabilisation de leur interface contract.

QA final.

Security selon l'analyse de risque.

---

## SCENARIO 4

"Un utilisateur peut modifier le logement d'un autre."

Attendu :

BUG.

Impact :

SECURITY  
AUTHORIZATION

Risk HIGH ou CRITICAL selon l'existant.

Security Reviewer prioritaire.

Tests de régression obligatoires.

---

## SCENARIO 5

"Refactorise le système de réservation."

Attendu :

inspection approfondie avant architecture.

Aucune abstraction prématurée.

Team Lead.

Architect uniquement si décision significative.

Tests de non-régression.

---

## SCENARIO 6

"Ajoute une authentification complète avec vérification email."

Attendu :

FULL.

FEATURE.

Impacts :

SECURITY  
AUTHENTICATION  
DATABASE  
UI  
EMAIL  
TESTING

Requirements.

Team Lead.

Architect si nécessaire.

Database analysis.

Symfony Developer.

Frontend.

QA.

Security.

Code Review.

---

## SCENARIO 7

"Ajoute la suppression du compte utilisateur."

Le système doit détecter que cette demande peut avoir des impacts :

DATABASE  
SECURITY  
AUTHORIZATION  
PRIVACY

Il doit inspecter les relations Doctrine avant de décider :

- cascade ;
- suppression ;
- anonymisation ;
- blocage.

Il ne doit pas inventer silencieusement une règle métier irréversible si plusieurs comportements métier incompatibles restent possibles.

---

# 27. TEST DE PARALLÉLISATION

Construis au moins un scénario interne démontrant :

- deux tâches réellement parallélisables ;
- leurs fichiers ;
- leurs inputs ;
- leurs outputs ;
- leur interface contract ;
- leur synchronisation avant intégration.

Construis également un contre-exemple où deux tâches semblent indépendantes mais doivent être sérialisées à cause d'un contrat non stabilisé.

---

# 28. TEST DE CORRECTION

Simule :

Developer  
→ QA finding QA-001 MAJOR  
→ correction  
→ QA revalidation

Puis :

Security Reviewer  
→ SEC-001 MAJOR  
→ Developer DISPUTED  
→ Team Lead arbitration  
→ UPHELD ou DISMISSED selon les preuves.

Vérifie que les compteurs de correction fonctionnent par finding.

---

# 29. TEST DE VALIDATION DOCKER

Vérifie réellement comment lancer les commandes dans ce repository.

Détermine :

- service applicatif ;
- working directory ;
- état Docker ;
- commande Symfony ;
- commande PHPUnit ;
- commande Composer.

N'invente aucune commande spécifique au repository sans inspection.

Lorsque possible, exécute des commandes non destructives permettant de vérifier que la stratégie fonctionne.

Ne lance aucune migration destructive uniquement pour tester l'orchestrateur.

---

# 30. CONTRAINTES DE QUALITÉ DE LA REFONTE

Priorités :

1. fiabilité ;
2. autonomie ;
3. déterminisme ;
4. sécurité ;
5. qualité Symfony ;
6. simplicité ;
7. faible consommation de contexte ;
8. faible duplication ;
9. parallélisation utile ;
10. coût raisonnable.

Évite :

- prompts gigantesques ;
- duplication ;
- agents inutiles ;
- pseudo-parallélisation ;
- architecture théorique impossible à exécuter avec Codex ;
- état inutilement persistant ;
- bureaucratie sur les tâches triviales ;
- agents qui valident leur propre travail ;
- reviews systématiques sans raison ;
- documentation pédagogique inutile.

---

# 31. COMPATIBILITÉ AVEC CODEX

Tout mécanisme proposé doit être réellement utilisable par Codex dans ce repository.

Ne conçois pas un orchestrateur théorique nécessitant un moteur externe qui n'existe pas.

Si une fonctionnalité idéale n'est pas réellement supportée par Codex :

1. identifie la limitation ;
2. choisis le mécanisme le plus proche réellement disponible ;
3. documente brièvement cette adaptation.

Le système doit optimiser le comportement réel de Codex, pas simuler une plateforme multi-agents imaginaire.

---

# 32. ORDRE D'EXÉCUTION

Procède maintenant dans cet ordre :

1. lire `AGENTS.md` ;
2. inspecter tout `.ai/` ;
3. inspecter la configuration Codex ;
4. inspecter `docker-compose.yml` et l'environnement Docker ;
5. inspecter l'application Symfony dans `www/skeleton` ;
6. comprendre les conventions existantes ;
7. produire mentalement un diagnostic de l'orchestrateur actuel ;
8. identifier KEEP / IMPROVE / MERGE / REMOVE ;
9. concevoir les modifications minimales nécessaires ;
10. renforcer le protocole ;
11. renforcer l'Orchestrator ;
12. clarifier Team Lead vs Orchestrator ;
13. introduire Task State si pertinent ;
14. introduire Task Graph/DAG ;
15. introduire Interface Contracts ;
16. renforcer Context Packets ;
17. renforcer Findings ;
18. renforcer Validation Evidence ;
19. introduire les Execution Profiles ;
20. améliorer la classification multidimensionnelle ;
21. adapter les workflows ;
22. améliorer les agents sans les gonfler inutilement ;
23. améliorer les skills pertinents ;
24. centraliser correctement l'exécution Docker ;
25. vérifier toutes les références entre fichiers ;
26. rechercher contradictions et duplications ;
27. simuler les scénarios obligatoires ;
28. tester les commandes Docker non destructives pertinentes ;
29. corriger les défauts détectés ;
30. effectuer une dernière revue globale.

---

# 33. CRITÈRE DE RÉUSSITE

À la fin, le système doit permettre à l'utilisateur d'écrire simplement :

> "Ajoute la possibilité de supprimer son compte."

Codex doit pouvoir déterminer de manière autonome :

- la nature de la tâche ;
- ses impacts ;
- sa complexité ;
- son risque ;
- le profil d'exécution ;
- les exigences ;
- les ambiguïtés réellement importantes ;
- les agents nécessaires ;
- les agents inutiles ;
- les sous-tâches ;
- leurs dépendances ;
- leur ownership ;
- leur parallélisation ;
- leurs contrats ;
- les fichiers à inspecter ;
- les fichiers à modifier ;
- les tests nécessaires ;
- les reviews nécessaires ;
- les commandes Docker nécessaires ;
- les validations nécessaires ;
- les preuves de validation ;
- les corrections nécessaires ;
- les conditions de sortie.

Et surtout :

> Une tâche triviale doit rester triviale.

Le système ne doit jamais transformer une modification de texte ou de couleur en réunion virtuelle de douze agents.

---

# 34. RAPPORT FINAL

Après avoir réellement modifié les fichiers :

Affiche un rapport final synthétique contenant :

## Architecture

- fichiers ajoutés ;
- fichiers modifiés ;
- fichiers supprimés ;
- rôle des nouveaux mécanismes.

## Changements majeurs

Explique brièvement :

- Task State ;
- Task Graph ;
- Execution Profiles ;
- classification multidimensionnelle ;
- Interface Contracts ;
- Findings ;
- Validation Evidence ;
- gestion Docker.

## Compatibilité

Indique :

- mécanismes réellement utilisables par Codex ;
- éventuelles limitations techniques rencontrées.

## Docker

Indique :

- service applicatif détecté ;
- working directory détecté ;
- commandes utilisées.

## Validation

Indique :

- scénarios simulés ;
- commandes exécutées ;
- résultats ;
- éléments impossibles à valider automatiquement.

Ne fournis pas uniquement des recommandations.

MODIFIE réellement l'orchestrateur existant.

Ne modifie pas le code métier Symfony sauf nécessité absolue pour vérifier le fonctionnement de l'orchestration.

Commence maintenant par inspecter l'existant.
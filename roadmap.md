Roadmap de développement — Symfony 8 / Twig / Tailwind
PHASE 1 — Modèle de données Doctrine conforme au MPD
TASK 1.1 — Audit MPD → Doctrine

Objectif : préparer l’implémentation sans modifier le modèle métier.

Sous-tâches :

inventorier les entités actuellement présentes dans src/Entity ;
inventorier les migrations existantes ;
comparer l’existant au MPD officiel ;
établir la liste exacte des 19 tables :
User
Language
UserLanguage
UserStatus
AgeVerificationStatus
Country
Property
PropertyCategory
PropertyStatus
Equipment
PropertyEquipment
PropertyImage
Booking
BookingStatus
Price
FavoriteProperty
Review
Conversation
Message
identifier les PK simples ;
identifier les PK composites ;
identifier toutes les FK ;
déterminer les cardinalités Doctrine ;
déterminer les types PHP/Doctrine ;
vérifier les nullabilités ;
ne modifier ni nom de table, ni champ, ni relation sans arbitrage.

Validation :

MPD_ANALYSIS = APPROVED
TASK 1.2 — Référentiels utilisateur

Créer :

Language
id
label

Sous-tâches :

créer l’entité ;
mapper label ;
prévoir la relation avec UserLanguage.
UserStatus
id
label

Fixtures prévues :

ACTIVE
SUSPENDED
DELETED
AgeVerificationStatus
id
label

Fixtures prévues :

PENDING
VERIFIED
REJECTED
Country
id
label

Sous-tâches communes :

entité ;
mapping ;
contraintes ;
relations inverses pertinentes ;
fixtures ;
tests du mapping.
TASK 1.3 — Entité User

Implémenter exactement :

id
email
password
firstname
lastname
phone
avatar
bio
address
city
zip_code
birth_date
email_verified
term_accepted_at
deleted_at
roles

status_id
age_verification_status_id
country_id

Sous-tâches :

créer/adopter User ;
implémenter UserInterface ;
implémenter PasswordAuthenticatedUserInterface ;
mapper email ;
contrainte UNIQUE email ;
mapper password ;
mapper données personnelles ;
mapper birthDate ;
mapper emailVerified ;
mapper termAcceptedAt ;
mapper deletedAt ;
mapper roles ;
relation ManyToOne vers UserStatus ;
relation ManyToOne vers AgeVerificationStatus ;
relation ManyToOne vers Country ;
ajouter les relations inverses nécessaires :
properties ;
bookings ;
conversations ;
messages envoyés ;
langues ;
favoris ;
contrôler les méthodes générées ;
ne jamais exposer un setter permettant de modifier arbitrairement le propriétaire de ressources métier.
TASK 1.4 — Relation UserLanguage

Le MPD impose une table associative explicite :

user_id      PK/FK
language_id  PK/FK

Sous-tâches :

créer l’association conformément au MPD ;
clé composite ;
FK User ;
FK Language ;
empêcher les doublons ;
ajouter les méthodes de manipulation côté User ;
tester ajout/suppression d’une langue.
TASK 1.5 — Référentiels logement
PropertyCategory
id
label
PropertyStatus
id
label

Fixtures :

DRAFT
PUBLISHED
HIDDEN
Equipment
id
label

Sous-tâches :

créer les trois entités ;
relations inverses ;
fixtures de démonstration ;
tester mapping.
TASK 1.6 — Entité Property

Implémenter exactement le MPD :

id

title
description

max_guest
bedrooms
bathrooms
beds
area_m2

address
city
zip_code

deposit
cleaning_fee

review_count
average_rating

nightly_price
published_at
weekend_price

pets_allowed

created_at
updated_at
deleted_at

user_id
country_id
category_id
status_id

Sous-tâches :

créer l’entité ;
typer tous les champs ;
utiliser des entiers pour les montants ;
relation propriétaire → User ;
relation → Country ;
relation → PropertyCategory ;
relation → PropertyStatus ;
initialiser les collections :
images ;
équipements ;
bookings ;
prices ;
favoris ;
conversations ;
gérer createdAt ;
gérer updatedAt ;
gérer publishedAt ;
gérer soft-delete via deletedAt ;
conserver reviewCount et averageRating puisque présents dans le MPD ;
ne pas encore implémenter leur calcul métier ;
contrôler les index futurs.
TASK 1.7 — Association PropertyEquipment

Le MPD impose :

property_id   PK/FK
equipment_id  PK/FK

Sous-tâches :

créer la relation associative ;
clé composite ;
FK vers Property ;
FK vers Equipment ;
empêcher doublons ;
méthodes add/remove ;
vérifier les cascades ;
ne jamais supprimer un Equipment simplement parce qu’un Property le retire.
TASK 1.8 — Entité PropertyImage
id
image
display_order
is_main
property_id

Sous-tâches :

créer entité ;
relation Property ;
mapping image ;
displayOrder ;
isMain ;
collection côté Property ;
ne pas encore développer le système d’upload.
TASK 1.9 — Référentiel BookingStatus
id
label

Fixtures :

PENDING
ACCEPTED
REJECTED
CANCELLED
TASK 1.10 — Entité Booking

Implémenter :

id

check_in
check_out

adult_count
children_count

night_subtotal
cleaning_fee
deposit
total_amount

cancellation_reason
cancellation_date

status_id
property_id
user_id

Sous-tâches :

mapping dates ;
occupants ;
snapshots financiers ;
annulation ;
relation BookingStatus ;
relation Property ;
relation User voyageur ;
collections/inverse mapping ;
contraintes :
checkOut > checkIn ;
adultes >= 1 ;
enfants >= 0 ;
montants >= 0 ;
préparer index de recherche des chevauchements.
TASK 1.11 — Entité Price
id
day
price_night
is_block
property_id

Sous-tâches :

créer entité ;
relation Property ;
contrainte d’unicité Property + day ;
index ;
mapping prix entier ;
mapping blocage de journée.

La fonctionnalité calendrier sera développée beaucoup plus tard.

TASK 1.12 — Association FavoriteProperty
user_id      PK/FK
property_id  PK/FK

Sous-tâches :

clé composite ;
relations User/Property ;
empêcher doublon ;
mapping inverse ;
aucune fonctionnalité UI pour l’instant.
TASK 1.13 — Entité Review
id
rating
comment
host_reply
created_at
host_reply_date
is_display
booking_id

Sous-tâches :

relation Booking ;
types exacts ;
rating entier ;
timestamps ;
réponse hôte nullable ;
visibilité ;
préparer relation unique éventuelle Booking ↔ Review selon contrainte métier à confirmer.
TASK 1.14 — Entité Conversation
id
created_at
last_message_at
user_id
property_id

Sous-tâches :

créer entité ;
relation User ;
relation Property ;
relation Messages ;
timestamps.
TASK 1.15 — Entité Message
id
content
read_at
conversation_id
sender_id

Sous-tâches :

relation Conversation ;
relation User sender ;
contenu ;
lecture ;
collections inverses.
TASK 1.16 — Contraintes et index

Vérifier notamment :

UNIQUE User.email

UNIQUE Price(property_id, day)

PK UserLanguage(user_id, language_id)

PK PropertyEquipment(property_id, equipment_id)

PK FavoriteProperty(user_id, property_id)

Et les index nécessaires :

Property(status_id, city)

Property(user_id, status_id)

Booking(property_id, status_id, check_in, check_out)

Booking(user_id, status_id)
TASK 1.17 — Migration Doctrine complète

Sous-tâches :

générer migration ;
auditer SQL ;
contrôler :
tables ;
colonnes ;
types ;
FK ;
PK composites ;
unique ;
index ;
nullabilité ;
ON DELETE ;
aucune cascade destructive sur historique métier.
TASK 1.18 — Fixtures initiales

Créer les référentiels :

Language
Country
UserStatus
AgeVerificationStatus
PropertyCategory
PropertyStatus
Equipment
BookingStatus

Puis :

deux comptes USER ;
un ADMIN ;
quelques propriétés ;
quelques images ;
éventuellement réservations de démonstration.
TASK 1.19 — Validation MPD finale

Faire un audit :

MPD                           DOCTRINE
User.email             ↔      User::$email
Property.user_id       ↔      Property::$user
Booking.property_id    ↔      Booking::$property
...

Vérifier 100 % des tables, champs et relations.

Puis :

base vide
→ migration
→ fixtures
→ schema:validate
→ tests

Gate obligatoire :

MPD_COMPLIANCE = APPROVED

avant Phase 2.

PHASE 2 — Authentification
TASK 2.1 — Inscription

Sous-tâches :

créer RegistrationFormType ;
firstname ;
lastname ;
birth date ;
email ;
password ;
confirmation ;
consentement ;
validations ;
email unique ;
PasswordHasher ;
RegistrationController ;
attribuer ROLE_USER ;
statut ACTIVE ;
statut âge initial ;
emailVerified = false ;
enregistrer termAcceptedAt ;
template Twig ;
gestion erreurs ;
flash ;
redirection ;
tests fonctionnels.
TASK 2.2 — Contrôle majorité

Sous-tâches :

service dédié ;
calcul depuis birthDate ;
jamais stocker l'âge ;
jour exact des 18 ans ;
tests unitaires ;
intégration actions sensibles.
TASK 2.3 — Connexion
Security config ;
login form ;
CSRF ;
session ;
blocage SUSPENDED ;
erreurs ;
redirection ;
template ;
tests.
TASK 2.4 — Déconnexion
firewall logout ;
navbar ;
redirection ;
test destruction session.
PHASE 3 — Profil utilisateur
TASK 3.1 — Consultation du compte
/account ;
controller ;
affichage données ;
langues ;
statut ;
avatar ;
navigation.
TASK 3.2 — Modification du profil
ProfileFormType ;
firstname/lastname ;
téléphone ;
avatar si retenu ;
bio ;
adresse ;
ville ;
CP ;
pays ;
langues ;
validation ;
sécurité ;
tests.
PHASE 4 — Gestion des logements
TASK 4.1 — Création d’un logement
PropertyFormType ;
informations générales ;
capacité ;
chambres ;
salles de bain ;
lits ;
surface ;
adresse ;
pays ;
catégorie ;
équipements ;
animaux ;
prix ;
propriétaire injecté serveur ;
statut DRAFT ;
majorité ;
validation ;
template ;
tests.
TASK 4.2 — Liste de mes logements
/host/properties ;
repository owner ;
PropertyCard ;
statut ;
actions ;
empty state ;
tests de cloisonnement.
TASK 4.3 — Modification
route edit ;
PropertyVoter ;
FormType ;
owner/admin ;
403 étranger ;
tests.
TASK 4.4 — Publication
contrôleur/action POST ;
critère de complétude ;
Voter ;
majorité ;
DRAFT → PUBLISHED ;
publishedAt ;
CSRF ;
tests.
TASK 4.5 — Masquage
POST ;
CSRF ;
owner/admin ;
→ HIDDEN ;
confirmation ;
tests.
PHASE 5 — Images
TASK 5.1 — Upload image principale
champ upload ;
MIME réel ;
taille ;
nom aléatoire ;
stockage local ;
création PropertyImage ;
isMain = true ;
tests.
TASK 5.2 — Remplacement image
uploader nouvelle ;
mise à jour DB ;
supprimer ancienne après succès ;
gestion erreur.
PHASE 6 — Catalogue public
TASK 6.1 — Accueil
logements publiés ;
PropertyCard ;
navigation ;
aucun hard-code.
TASK 6.2 — Catalogue
uniquement PUBLISHED ;
exclure deletedAt ;
repository ;
template ;
N+1 check.
TASK 6.3 — Pagination
pages bornées ;
taille ;
URLs ;
filtres conservés.
TASK 6.4 — Recherche ville
query param ;
repository ;
validation ;
état vide.
TASK 6.5 — Filtre catégorie
liste catégories ;
paramètre ;
combinaison ville + catégorie ;
pagination.
PHASE 7 — Fiche logement
TASK 7.1 — Détail public
/properties/{id} ;
uniquement public ;
image ;
caractéristiques ;
équipements ;
prix ;
animaux ;
propriétaire ;
404/403 approprié ;
tests.
PHASE 8 — Réservations
TASK 8.1 — Calcul du prix

Créer BookingPriceCalculator :

night_subtotal
cleaning_fee
deposit
total_amount

Sous-tâches :

nombre de nuits ;
nightlyPrice ;
week-end non encore appliqué sauf décision ;
snapshots ;
aucun montant accepté du navigateur ;
tests.
TASK 8.2 — Vérification capacité
adultCount + childrenCount <= maxGuest

Tests limites.

TASK 8.3 — Disponibilité
méthode repository ;
formule [A,B[ ;
bookings ACCEPTED ;
dates adjacentes autorisées ;
tests inclusion/partiel/égalité.
TASK 8.4 — Demande de réservation
BookingFormType ;
dates ;
adultes ;
enfants ;
éventuel message métier à traiter séparément si pas présent dans Booking ;
validation serveur ;
capacité ;
disponibilité ;
prix ;
status PENDING ;
current user ;
snapshot ;
flash ;
tests.

Point critique : ton MPD Booking ne contient pas de champ message. Le CDC parle d’un message facultatif lors d’une demande. Il faudra donc trancher avant cette tâche : soit supprimer cette exigence du noyau, soit la modéliser ailleurs. Je ne ferais pas ajouter silencieusement Booking.message, puisque ton MPD est désormais la source de vérité.

PHASE 9 — Gestion des réservations hôte
TASK 9.1 — Demandes reçues
/host/bookings ;
Booking.property.user = CurrentUser ;
filtres ;
badges ;
empty state.
TASK 9.2 — Acceptation
BookingVoter ;
POST ;
CSRF ;
PENDING uniquement ;
transaction ;
revalidation chevauchement ;
ACCEPTED ;
tests concurrents/logiques.
TASK 9.3 — Refus
owner ;
PENDING ;
REJECTED ;
CSRF ;
tests.
TASK 9.4 — Annulation

Puisque le MPD contient :

cancellation_reason
cancellation_date
CANCELLED

la fonctionnalité a désormais une place naturelle.

Il reste à définir la règle produit :

qui peut annuler ;
jusqu’à quand ;
quels états ;
raison obligatoire ou non.
PHASE 10 — Univers utilisateur
TASK 10.1 — Univers voyageur

/traveler/bookings

réservations effectuées ;
Property ;
dates ;
statut ;
montant ;
annulation si autorisée.
TASK 10.2 — Univers hôte
propriétés ;
demandes reçues ;
PENDING ;
actions rapides.
TASK 10.3 — Dashboard compte
navigation voyageur/hôte ;
profil ;
données de synthèse.
PHASE 11 — Administration
TASK 11.1 — Sécurisation /admin
ROLE_ADMIN ;
access_control ;
tests USER.
TASK 11.2 — Utilisateurs
liste ;
recherche ;
statut ;
pagination.
TASK 11.3 — Suspension
POST ;
CSRF ;
ACTIVE → SUSPENDED ;
blocage login ;
tests.
TASK 11.4 — Logements
liste globale ;
filtre ;
statut ;
propriétaire.
TASK 11.5 — Modération Property
masquer ;
CSRF ;
admin ;
tests.
PHASE 12 — UX / Design / Responsive
TASK 12.1 — Design system Twig/Tailwind

Créer une cohérence sur :

typography ;
containers ;
boutons ;
inputs ;
badges ;
cards ;
alerts ;
spacing.
TASK 12.2 — Navbar

États :

visiteur
USER voyageur
USER hôte
ADMIN

sans créer de nouveaux rôles.

TASK 12.3 — Composants Twig
PropertyCard ;
StatusBadge ;
Button ;
Pagination ;
EmptyState ;
Flash ;
Modal/confirmation si nécessaire.
TASK 12.4 — Responsive

Valider :

360
430
768
1440
1920px
TASK 12.5 — Accessibilité
clavier ;
focus ;
labels ;
contraste ;
erreurs ;
HTML sémantique.
PHASE 13 — Favoris

Cette phase exploite maintenant FavoriteProperty.

TASK 13.1 — Ajouter aux favoris
authentification ;
action POST ;
CSRF ;
éviter doublons ;
lien User/Property ;
UI ;
tests.
TASK 13.2 — Retirer des favoris
owner de la relation ;
POST ;
CSRF ;
tests.
TASK 13.3 — Mes favoris
liste ;
PropertyCard ;
exclure propriétés non consultables ;
état vide.
PHASE 14 — Tarification et calendrier avancés

Exploite Price.

TASK 14.1 — Prix journalier
CRUD Price ;
date unique/property ;
priceNight ;
validation.
TASK 14.2 — Blocage de dates
isBlock ;
calendrier ;
disponibilité ;
tests.
TASK 14.3 — Prix week-end

Le MPD contient :

Property.weekend_price
définir jours concernés ;
intégrer PriceCalculator ;
priorité Price.day vs weekendPrice vs nightlyPrice ;
tests.
TASK 14.4 — Calendrier hôte
affichage ;
jours bloqués ;
réservations ;
tarifs spécifiques ;
Stimulus si utile.
PHASE 15 — Avis
TASK 15.1 — Déposer un avis
Booking éligible ;
rating ;
comment ;
date ;
une réservation seulement ;
validation ;
sécurité ;
tests.
TASK 15.2 — Affichage des avis
isDisplay ;
fiche Property ;
pagination si besoin.
TASK 15.3 — Réponse hôte
propriétaire uniquement ;
hostReply ;
hostReplyDate ;
tests.
TASK 15.4 — Agrégats Property

Mettre à jour :

review_count
average_rating
stratégie de recalcul ;
cohérence création/modification/masquage ;
tests.
PHASE 16 — Messagerie
TASK 16.1 — Créer/ouvrir une Conversation

Relation :

User
Property
vérifier accès ;
éviter conversations incohérentes ;
createdAt.
TASK 16.2 — Liste conversations
utilisateur courant ;
propriété ;
dernier message ;
lastMessageAt.
TASK 16.3 — Envoyer Message
conversation autorisée ;
sender = current user ;
content ;
validation ;
mise à jour lastMessageAt.
TASK 16.4 — Lecture
readAt ;
seulement destinataire pertinent ;
compteur éventuel calculé.
PHASE 17 — Email vérifié

Ton MPD contient email_verified.

Comme le CDC le positionne en extension :

TASK 17.1
génération token ;
email ;
route confirmation ;
expiration ;
emailVerified = true ;
tests.
TASK 17.2 — Mot de passe oublié
demande ;
token ;
mail ;
reset ;
invalidation ;
tests sécurité.
PHASE 18 — Sécurité et tests finaux
TASK 18.1 — Tests unitaires
majorité ;
price calculator ;
week-end ;
disponibilité ;
transitions ;
average rating.
TASK 18.2 — Tests fonctionnels
inscription ;
login ;
Property ;
publication ;
Booking ;
accept/reject ;
favorites ;
reviews ;
messaging selon fonctionnalités réalisées.
TASK 18.3 — Tests sécurité

Deux users + admin :

Property étrangère ;
Booking étrangère ;
review étrangère ;
conversation étrangère ;
favoris ;
/admin ;
manipulation IDs ;
CSRF.
TASK 18.4 — Performance
N+1 catalogue ;
N+1 dashboards ;
indexes ;
pagination ;
requêtes reviews/messages.
PHASE 19 — Documentation et recette
TASK 19.1 — README
installation ;
Docker ;
migrations ;
fixtures ;
tests ;
comptes ;
architecture ;
commandes.
TASK 19.2 — Recette complète

Scénario :

USER A
  ↓
inscription
  ↓
création Property
  ↓
publication

USER B
  ↓
recherche
  ↓
réservation

USER A
  ↓
acceptation

USER B
  ↓
consultation Booking

Puis extensions :

favori
avis
réponse hôte
messagerie
calendrier

Et administration :

ADMIN
→ suspension USER
→ modération Property
Vue globale du fil rouge
PHASE 1
MPD / ENTITÉS / MIGRATIONS
        ↓
PHASE 2
AUTHENTIFICATION
        ↓
PHASE 3
PROFIL
        ↓
PHASE 4
PROPERTY
        ↓
PHASE 5
IMAGES
        ↓
PHASE 6
CATALOGUE / RECHERCHE
        ↓
PHASE 7
FICHE PROPERTY
        ↓
PHASE 8
BOOKING / PRICING / DISPONIBILITÉ
        ↓
PHASE 9
GESTION BOOKING HÔTE
        ↓
PHASE 10
UNIVERS VOYAGEUR / HÔTE
        ↓
PHASE 11
ADMINISTRATION
        ↓
PHASE 12
UX / RESPONSIVE
─────────────────────────────
       NOYAU VALIDÉ
─────────────────────────────
        ↓
PHASE 13
FAVORIS
        ↓
PHASE 14
CALENDRIER / PRICE
        ↓
PHASE 15
AVIS
        ↓
PHASE 16
MESSAGERIE
        ↓
PHASE 17
EMAIL / PASSWORD RESET
        ↓
PHASE 18
HARDENING / TESTS
        ↓
PHASE 19
RECETTE / DOCUMENTATION
Ce que je considère comme le bon niveau de granularité

Pour ton orchestrateur, TASK 4.1 — Création d'un logement est une mission, et les éléments PropertyFormType, Controller, validations, Twig, Voter éventuel et tests sont ses sous-tâches.

Je n'irais pas jusqu'à transformer chaque fichier en story. Sinon tu pilotes Codex à sa place et ton Team Lead/Orchestrator ne sert plus à grand-chose.

Et il y a désormais un seul point de divergence explicite entre ton CDC et le MPD qu'il faudra arbitrer avant la phase Booking : le CDC prévoit un message facultatif sur une demande de réservation, alors que ton MPD officiel ne possède aucun Booking.message. Puisque tu viens de définir le MPD comme référence stricte, Codex ne devra surtout pas ajouter ce champ tout seul.
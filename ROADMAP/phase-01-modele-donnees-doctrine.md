# Phase 01 - Modele de Donnees Doctrine Conforme au MPD

## Gate de Phase

- [ ] `MPD_ANALYSIS = APPROVED` avant modification du modele.
- [ ] `MPD_COMPLIANCE = APPROVED` avant Phase 2.
- [ ] Migration depuis base vide executee.
- [ ] Fixtures chargees.
- [ ] `docker compose exec apache_app_airbnb php bin/console doctrine:schema:validate` passe.
- [ ] Tests Doctrine pertinents passent.

## Garde-fous CDC vs MPD

Le MPD fourni sert de reference structurelle pour la Phase 1, mais il ne doit pas etre interprete automatiquement comme un ordre d'implementation fonctionnelle immediate.

- [ ] Distinguer les tables du MPD a creer pour integrite structurelle des fonctionnalites a developper plus tard.
- [ ] Surveiller l'ecart `Language` / `UserLanguage`: present dans le MPD, absent de l'inventaire de donnees page 8 du CDC.
- [ ] Surveiller l'ecart `Notification`: prevu dans le CDC comme extension ulterieure, absent du MPD fourni.
- [ ] Surveiller l'ecart `AdminActionLog`: prevu dans le CDC comme extension ulterieure, absent du MPD fourni.
- [ ] Ne pas creer `Notification` ni `AdminActionLog` en Phase 1 sans arbitrage MPD explicite.
- [ ] Conserver les champs d'extension presents dans le MPD, comme `Property.review_count` et `Property.average_rating`, sans developper la fonctionnalite avis dans le noyau.
- [ ] Ne pas considerer automatiquement une table ou un champ MPD comme fonctionnalite livrable maintenant.
- [ ] Reporter tout ecart CDC/MPD dans l'audit `TASK 1.1` avant generation de migration.

## TASK 1.1 - Audit MPD vers Doctrine

- [ ] Inventorier les entites presentes dans `www/src/Entity`.
- [ ] Inventorier les migrations existantes.
- [ ] Comparer l'existant au MPD officiel.
- [ ] Comparer le MPD avec l'inventaire de donnees du CDC.
- [ ] Classer chaque ecart CDC/MPD: `A_IMPLEMENTER_PHASE_1`, `STRUCTURE_SEULE`, `EXTENSION_ULTERIEURE`, `ARBITRAGE_REQUIS`.
- [ ] Confirmer la liste des 19 tables MPD.
- [ ] Confirmer que `Notification` est hors Phase 1 tant qu'absent du MPD.
- [ ] Confirmer que `AdminActionLog` est hors Phase 1 tant qu'absent du MPD.
- [ ] Confirmer le statut de `Language` / `UserLanguage` malgre leur absence de l'inventaire CDC page 8.
- [ ] Identifier les PK simples.
- [ ] Identifier les PK composites.
- [ ] Identifier toutes les FK.
- [ ] Determiner les cardinalites Doctrine.
- [ ] Determiner les types PHP/Doctrine.
- [ ] Verifier les nullabilites.
- [ ] Documenter les ecarts sans modifier le modele.
- [ ] Documenter les champs structurels lies a des extensions futures sans implementer leur logique metier.
- [ ] Validation: `MPD_ANALYSIS = APPROVED`.

Tables a confirmer:

- [ ] User
- [ ] Language
- [ ] UserLanguage
- [ ] UserStatus
- [ ] AgeVerificationStatus
- [ ] Country
- [ ] Property
- [ ] PropertyCategory
- [ ] PropertyStatus
- [ ] Equipment
- [ ] PropertyEquipment
- [ ] PropertyImage
- [ ] Booking
- [ ] BookingStatus
- [ ] Price
- [ ] FavoriteProperty
- [ ] Review
- [ ] Conversation
- [ ] Message

Typage MPD a respecter:

Note: l'image MPD fournit les types et les relations, mais ne precise pas toutes les nullabilites. Toute nullabilite doit donc etre confirmee pendant `TASK 1.1`.

### User

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `email` | `varchar(150)` | `string(150)` |
| `password` | `varchar` | `string` |
| `firstname` | `varchar` | `string` |
| `lastname` | `varchar` | `string` |
| `phone` | `varchar` | `string` |
| `avatar` | `varchar` | `string` |
| `bio` | `text` | `text` / `string` |
| `address` | `varchar` | `string` |
| `city` | `varchar` | `string` |
| `zip_code` | `varchar` | `string` |
| `birth_date` | `date` | `date_immutable` ou `date` / `DateTimeInterface` |
| `email_verified` | `bool` | `boolean` / `bool` |
| `term_accepted_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `deleted_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `roles` | `array` | `json` ou `array` / `array` |
| `status_id` | `relation` | `ManyToOne UserStatus` |
| `age_verification_status_id` | `relation` | `ManyToOne AgeVerificationStatus` |
| `country_id` | `relation` | `ManyToOne Country` |

### Referentiels utilisateur

| Table | Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|---|
| `Language` | `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `Language` | `label` | `varchar(100)` | `string(100)` |
| `UserStatus` | `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `UserStatus` | `label` | `varchar(100)` | `string(100)` |
| `AgeVerificationStatus` | `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `AgeVerificationStatus` | `label` | `varchar(100)` | `string(100)` |
| `Country` | `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `Country` | `label` | `varchar(100)` | `string(100)` |

### UserLanguage

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `user_id` | `relation` | `ManyToOne User`, PK/FK |
| `language_id` | `relation` | `ManyToOne Language`, PK/FK |

### Property

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `title` | `varchar` | `string` |
| `description` | `text` | `text` / `string` |
| `max_guest` | `int` | `integer` / `int` |
| `bedrooms` | `int` | `integer` / `int` |
| `bathrooms` | `int` | `integer` / `int` |
| `beds` | `int` | `integer` / `int` |
| `area_m2` | `int` | `integer` / `int` |
| `address` | `varchar` | `string` |
| `city` | `varchar` | `string` |
| `zip_code` | `varchar` | `string` |
| `deposit` | `int` | `integer` / `int` |
| `cleaning_fee` | `int` | `integer` / `int` |
| `review_count` | `int` | `integer` / `int` |
| `average_rating` | `float` | `float` |
| `nightly_price` | `int` | `integer` / `int` |
| `published_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `weekend_price` | `int` | `integer` / `int` |
| `pets_allowed` | `bool` | `boolean` / `bool` |
| `created_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `updated_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `deleted_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `user_id` | `relation` | `ManyToOne User` |
| `country_id` | `relation` | `ManyToOne Country` |
| `category_id` | `relation` | `ManyToOne PropertyCategory` |
| `status_id` | `relation` | `ManyToOne PropertyStatus` |

### Referentiels logement

| Table | Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|---|
| `PropertyCategory` | `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `PropertyCategory` | `label` | `varchar(100)` | `string(100)` |
| `PropertyStatus` | `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `PropertyStatus` | `label` | `varchar(100)` | `string(100)` |
| `Equipment` | `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `Equipment` | `label` | `varchar(100)` | `string(100)` |

### PropertyEquipment

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `property_id` | `relation` | `ManyToOne Property`, PK/FK |
| `equipment_id` | `relation` | `ManyToOne Equipment`, PK/FK |

### PropertyImage

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `image` | `varchar` | `string` |
| `display_order` | `int` | `integer` / `int` |
| `is_main` | `bool` | `boolean` / `bool` |
| `property_id` | `relation` | `ManyToOne Property` |

### Booking

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `check_in` | `date` | `date_immutable` ou `date` / `DateTimeInterface` |
| `check_out` | `date` | `date_immutable` ou `date` / `DateTimeInterface` |
| `adult_count` | `int` | `integer` / `int` |
| `children_count` | `int` | `integer` / `int` |
| `night_subtotal` | `int` | `integer` / `int` |
| `cleaning_fee` | `int` | `integer` / `int` |
| `deposit` | `int` | `integer` / `int` |
| `total_amount` | `int` | `integer` / `int` |
| `cancellation_reason` | `text` | `text` / `string` |
| `cancellation_date` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `status_id` | `relation` | `ManyToOne BookingStatus` |
| `property_id` | `relation` | `ManyToOne Property` |
| `user_id` | `relation` | `ManyToOne User` |

### BookingStatus

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `label` | `varchar(100)` | `string(100)` |

### Price

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `day` | `date` | `date_immutable` ou `date` / `DateTimeInterface` |
| `price_night` | `int` | `integer` / `int` |
| `is_block` | `bool` | `boolean` / `bool` |
| `property_id` | `relation` | `ManyToOne Property` |

### FavoriteProperty

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `user_id` | `relation` | `ManyToOne User`, PK/FK |
| `property_id` | `relation` | `ManyToOne Property`, PK/FK |

### Review

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `rating` | `int` | `integer` / `int` |
| `comment` | `text` | `text` / `string` |
| `host_reply` | `text` | `text` / `string` |
| `created_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `host_reply_date` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `is_display` | `bool` | `boolean` / `bool` |
| `booking_id` | `relation` | `OneToOne` ou `ManyToOne Booking` selon arbitrage |

### Conversation

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `created_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `last_message_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `user_id` | `relation` | `ManyToOne User` |
| `property_id` | `relation` | `ManyToOne Property` |

### Message

| Champ MPD | Type MPD | Type Doctrine/PHP attendu |
|---|---|---|
| `id` | `UniqueID` | `uuid/guid` ou equivalent projet |
| `content` | `text` | `text` / `string` |
| `read_at` | `datetime` | `datetime_immutable` ou `datetime` / `DateTimeInterface` |
| `conversation_id` | `relation` | `ManyToOne Conversation` |
| `sender_id` | `relation` | `ManyToOne User` |

## TASK 1.2 - Referentiels Utilisateur

- [ ] Creer `Language`.
- [ ] Mapper `Language.id`.
- [ ] Mapper `Language.label`.
- [ ] Prevoir relation avec `UserLanguage`.
- [ ] Creer `UserStatus`.
- [ ] Mapper `UserStatus.id`.
- [ ] Mapper `UserStatus.label`.
- [ ] Ajouter fixtures `ACTIVE`, `SUSPENDED`, `DELETED`.
- [ ] Creer `AgeVerificationStatus`.
- [ ] Mapper `AgeVerificationStatus.id`.
- [ ] Mapper `AgeVerificationStatus.label`.
- [ ] Ajouter fixtures `PENDING`, `VERIFIED`, `REJECTED`.
- [ ] Creer `Country`.
- [ ] Mapper `Country.id`.
- [ ] Mapper `Country.label`.
- [ ] Ajouter contraintes.
- [ ] Ajouter relations inverses pertinentes.
- [ ] Tester mapping des referentiels.

## TASK 1.3 - Entite User

- [ ] Creer ou adopter `User`.
- [ ] Implementer `UserInterface`.
- [ ] Implementer `PasswordAuthenticatedUserInterface`.
- [ ] Mapper `id`.
- [ ] Mapper `email`.
- [ ] Ajouter contrainte `UNIQUE email`.
- [ ] Mapper `password`.
- [ ] Mapper `firstname`.
- [ ] Mapper `lastname`.
- [ ] Mapper `phone`.
- [ ] Mapper `avatar`.
- [ ] Mapper `bio`.
- [ ] Mapper `address`.
- [ ] Mapper `city`.
- [ ] Mapper `zip_code`.
- [ ] Mapper `birth_date`.
- [ ] Mapper `email_verified`.
- [ ] Mapper `term_accepted_at`.
- [ ] Mapper `deleted_at`.
- [ ] Mapper `roles`.
- [ ] Relation ManyToOne vers `UserStatus`.
- [ ] Relation ManyToOne vers `AgeVerificationStatus`.
- [ ] Relation ManyToOne vers `Country`.
- [ ] Ajouter relation inverse `properties`.
- [ ] Ajouter relation inverse `bookings`.
- [ ] Ajouter relation inverse `conversations`.
- [ ] Ajouter relation inverse `messages envoyes`.
- [ ] Ajouter relation inverse `langues`.
- [ ] Ajouter relation inverse `favoris`.
- [ ] Controler les methodes generees.
- [ ] Eviter setter arbitraire du proprietaire de ressources metier.

## TASK 1.4 - Relation UserLanguage

- [ ] Creer association explicite `UserLanguage`.
- [ ] Mapper PK composite `user_id`, `language_id`.
- [ ] Mapper FK vers `User`.
- [ ] Mapper FK vers `Language`.
- [ ] Empêcher les doublons.
- [ ] Ajouter methodes de manipulation cote `User`.
- [ ] Tester ajout d'une langue.
- [ ] Tester suppression d'une langue.

## TASK 1.5 - Referentiels Logement

- [ ] Creer `PropertyCategory`.
- [ ] Mapper `PropertyCategory.id`.
- [ ] Mapper `PropertyCategory.label`.
- [ ] Creer `PropertyStatus`.
- [ ] Mapper `PropertyStatus.id`.
- [ ] Mapper `PropertyStatus.label`.
- [ ] Ajouter fixtures `DRAFT`, `PUBLISHED`, `HIDDEN`.
- [ ] Creer `Equipment`.
- [ ] Mapper `Equipment.id`.
- [ ] Mapper `Equipment.label`.
- [ ] Ajouter relations inverses pertinentes.
- [ ] Ajouter fixtures de demonstration.
- [ ] Tester mapping.

## TASK 1.6 - Entite Property

- [ ] Creer `Property`.
- [ ] Mapper `id`.
- [ ] Mapper `title`.
- [ ] Mapper `description`.
- [ ] Mapper `max_guest`.
- [ ] Mapper `bedrooms`.
- [ ] Mapper `bathrooms`.
- [ ] Mapper `beds`.
- [ ] Mapper `area_m2`.
- [ ] Mapper `address`.
- [ ] Mapper `city`.
- [ ] Mapper `zip_code`.
- [ ] Mapper `deposit` en entier.
- [ ] Mapper `cleaning_fee` en entier.
- [ ] Mapper `review_count`.
- [ ] Mapper `average_rating`.
- [ ] Mapper `nightly_price` en entier.
- [ ] Mapper `published_at`.
- [ ] Mapper `weekend_price` en entier.
- [ ] Mapper `pets_allowed`.
- [ ] Mapper `created_at`.
- [ ] Mapper `updated_at`.
- [ ] Mapper `deleted_at`.
- [ ] Relation proprietaire vers `User`.
- [ ] Relation vers `Country`.
- [ ] Relation vers `PropertyCategory`.
- [ ] Relation vers `PropertyStatus`.
- [ ] Initialiser collection `images`.
- [ ] Initialiser collection `equipements`.
- [ ] Initialiser collection `bookings`.
- [ ] Initialiser collection `prices`.
- [ ] Initialiser collection `favoris`.
- [ ] Initialiser collection `conversations`.
- [ ] Gerer `createdAt`.
- [ ] Gerer `updatedAt`.
- [ ] Gerer `publishedAt`.
- [ ] Gerer soft-delete via `deletedAt`.
- [ ] Conserver `reviewCount` et `averageRating` sans calcul metier.
- [ ] Controler les index futurs.

## TASK 1.7 - Association PropertyEquipment

- [ ] Creer association explicite `PropertyEquipment`.
- [ ] Mapper PK composite `property_id`, `equipment_id`.
- [ ] Mapper FK vers `Property`.
- [ ] Mapper FK vers `Equipment`.
- [ ] Empêcher les doublons.
- [ ] Ajouter methodes add/remove.
- [ ] Verifier cascades.
- [ ] Garantir qu'un retrait ne supprime pas `Equipment`.

## TASK 1.8 - Entite PropertyImage

- [ ] Creer `PropertyImage`.
- [ ] Mapper `id`.
- [ ] Mapper `image`.
- [ ] Mapper `display_order`.
- [ ] Mapper `is_main`.
- [ ] Mapper relation `property_id`.
- [ ] Ajouter collection cote `Property`.
- [ ] Ne pas developper l'upload.

## TASK 1.9 - Referentiel BookingStatus

- [ ] Creer `BookingStatus`.
- [ ] Mapper `id`.
- [ ] Mapper `label`.
- [ ] Ajouter fixtures `PENDING`, `ACCEPTED`, `REJECTED`, `CANCELLED`.

## TASK 1.10 - Entite Booking

- [ ] Creer `Booking`.
- [ ] Mapper `id`.
- [ ] Mapper `check_in`.
- [ ] Mapper `check_out`.
- [ ] Mapper `adult_count`.
- [ ] Mapper `children_count`.
- [ ] Mapper `night_subtotal`.
- [ ] Mapper `cleaning_fee`.
- [ ] Mapper `deposit`.
- [ ] Mapper `total_amount`.
- [ ] Mapper `cancellation_reason`.
- [ ] Mapper `cancellation_date`.
- [ ] Relation vers `BookingStatus`.
- [ ] Relation vers `Property`.
- [ ] Relation vers `User` voyageur.
- [ ] Ajouter collections/inverse mapping.
- [ ] Valider `checkOut > checkIn`.
- [ ] Valider `adultes >= 1`.
- [ ] Valider `enfants >= 0`.
- [ ] Valider `montants >= 0`.
- [ ] Preparer index de recherche des chevauchements.

## TASK 1.11 - Entite Price

- [ ] Creer `Price`.
- [ ] Mapper `id`.
- [ ] Mapper `day`.
- [ ] Mapper `price_night` en entier.
- [ ] Mapper `is_block`.
- [ ] Relation vers `Property`.
- [ ] Ajouter contrainte unique `Property + day`.
- [ ] Ajouter index.
- [ ] Ne pas developper le calendrier.

## TASK 1.12 - Association FavoriteProperty

- [ ] Creer association explicite `FavoriteProperty`.
- [ ] Mapper PK composite `user_id`, `property_id`.
- [ ] Mapper relation `User`.
- [ ] Mapper relation `Property`.
- [ ] Empêcher doublon.
- [ ] Ajouter mapping inverse.
- [ ] Ne pas developper l'UI.

## TASK 1.13 - Entite Review

- [ ] Creer `Review`.
- [ ] Mapper `id`.
- [ ] Mapper `rating` entier.
- [ ] Mapper `comment`.
- [ ] Mapper `host_reply`.
- [ ] Mapper `created_at`.
- [ ] Mapper `host_reply_date`.
- [ ] Mapper `is_display`.
- [ ] Relation vers `Booking`.
- [ ] Preparer relation unique `Booking` vers `Review` selon arbitrage metier.

## TASK 1.14 - Entite Conversation

- [ ] Creer `Conversation`.
- [ ] Mapper `id`.
- [ ] Mapper `created_at`.
- [ ] Mapper `last_message_at`.
- [ ] Relation vers `User`.
- [ ] Relation vers `Property`.
- [ ] Relation vers `Message`.
- [ ] Gerer timestamps.

## TASK 1.15 - Entite Message

- [ ] Creer `Message`.
- [ ] Mapper `id`.
- [ ] Mapper `content`.
- [ ] Mapper `read_at`.
- [ ] Relation vers `Conversation`.
- [ ] Relation vers `User sender`.
- [ ] Ajouter collections inverses.

## TASK 1.16 - Contraintes et Index

- [ ] Verifier `UNIQUE User.email`.
- [ ] Verifier `UNIQUE Price(property_id, day)`.
- [ ] Verifier `PK UserLanguage(user_id, language_id)`.
- [ ] Verifier `PK PropertyEquipment(property_id, equipment_id)`.
- [ ] Verifier `PK FavoriteProperty(user_id, property_id)`.
- [ ] Ajouter index `Property(status_id, city)`.
- [ ] Ajouter index `Property(user_id, status_id)`.
- [ ] Ajouter index `Booking(property_id, status_id, check_in, check_out)`.
- [ ] Ajouter index `Booking(user_id, status_id)`.

## TASK 1.17 - Migration Doctrine Complete

- [ ] Generer migration.
- [ ] Auditer SQL.
- [ ] Controler tables.
- [ ] Controler colonnes.
- [ ] Controler types.
- [ ] Controler FK.
- [ ] Controler PK composites.
- [ ] Controler unique.
- [ ] Controler index.
- [ ] Controler nullabilite.
- [ ] Controler `ON DELETE`.
- [ ] Garantir aucune cascade destructive sur historique metier.

## TASK 1.18 - Fixtures Initiales

- [ ] Fixtures `Language`.
- [ ] Fixtures `Country`.
- [ ] Fixtures `UserStatus`.
- [ ] Fixtures `AgeVerificationStatus`.
- [ ] Fixtures `PropertyCategory`.
- [ ] Fixtures `PropertyStatus`.
- [ ] Fixtures `Equipment`.
- [ ] Fixtures `BookingStatus`.
- [ ] Creer deux comptes `USER`.
- [ ] Creer un compte `ADMIN`.
- [ ] Creer quelques proprietes.
- [ ] Creer quelques images.
- [ ] Creer reservations de demonstration si utile.

## TASK 1.19 - Validation MPD Finale

- [ ] Auditer 100% des tables MPD versus Doctrine.
- [ ] Auditer 100% des champs MPD versus Doctrine.
- [ ] Auditer 100% des relations MPD versus Doctrine.
- [ ] Tester base vide vers migration.
- [ ] Charger fixtures.
- [ ] Lancer `docker compose exec apache_app_airbnb php bin/console doctrine:schema:validate`.
- [ ] Lancer tests pertinents.
- [ ] Validation: `MPD_COMPLIANCE = APPROVED`.

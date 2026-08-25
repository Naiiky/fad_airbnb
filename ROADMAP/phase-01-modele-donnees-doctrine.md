# Phase 01 - Modele de Donnees Doctrine Conforme au MPD

## Notes d'audit Phase 1

- `MPD_ANALYSIS = APPROVED`: le MPD visuel `airbnb-mpd.jpg` contient 19 tables metier confirmees; l'existant Doctrine etait vide hors `.gitignore`.
- `MPD_COMPLIANCE = APPROVED`: les 19 tables MPD sont mappees en entites Doctrine et validees par `doctrine:schema:validate`.
- Tables techniques hors MPD: `messenger_messages` et `doctrine_migration_versions` sont conservees car generees par les bundles Symfony/Doctrine deja configures.
- Ecart CDC/MPD: `Language` et `UserLanguage` sont implementes en structure Phase 1 car presents au MPD, meme absents de l'inventaire CDC page 8 mentionne par la roadmap.
- Ecart CDC/MPD: `Notification` et `AdminActionLog` restent hors Phase 1 car absents du MPD fourni.
- Champs d'extension: `Property.review_count`, `Property.average_rating`, `Review`, `Price`, `Conversation` et `Message` sont structures sans logique metier avancee.
- Nullabilites retenues: dates de publication/suppression/annulation/lecture/reponse hote et champs profil optionnels sont nullable; les FK structurelles principales sont non nullable.
- Cardinalites retenues: les FK MPD sont mappees en `ManyToOne`; `Review.booking` est arbitre en `OneToOne` avec unique sur `booking_id`; les tables `UserLanguage`, `PropertyEquipment` et `FavoriteProperty` ont des PK composites.
- Suppressions: pas de cascade Doctrine `remove` sur les historiques metier; `onDelete=RESTRICT` sur les relations historiques, cascades limitees aux lignes de liaison ou enfants strictement dependants.

## Gate de Phase

- [x] `MPD_ANALYSIS = APPROVED` avant modification du modele.
- [x] `MPD_COMPLIANCE = APPROVED` avant Phase 2.
- [x] Migration depuis base vide executee.
- [x] Fixtures chargees.
- [x] `docker compose exec apache_app_airbnb php bin/console doctrine:schema:validate` passe.
- [x] Tests Doctrine pertinents passent.

## Garde-fous CDC vs MPD

Le MPD fourni sert de reference structurelle pour la Phase 1, mais il ne doit pas etre interprete automatiquement comme un ordre d'implementation fonctionnelle immediate.

- [x] Distinguer les tables du MPD a creer pour integrite structurelle des fonctionnalites a developper plus tard.
- [x] Surveiller l'ecart `Language` / `UserLanguage`: present dans le MPD, absent de l'inventaire de donnees page 8 du CDC.
- [x] Surveiller l'ecart `Notification`: prevu dans le CDC comme extension ulterieure, absent du MPD fourni.
- [x] Surveiller l'ecart `AdminActionLog`: prevu dans le CDC comme extension ulterieure, absent du MPD fourni.
- [x] Ne pas creer `Notification` ni `AdminActionLog` en Phase 1 sans arbitrage MPD explicite.
- [x] Conserver les champs d'extension presents dans le MPD, comme `Property.review_count` et `Property.average_rating`, sans developper la fonctionnalite avis dans le noyau.
- [x] Ne pas considerer automatiquement une table ou un champ MPD comme fonctionnalite livrable maintenant.
- [x] Reporter tout ecart CDC/MPD dans l'audit `TASK 1.1` avant generation de migration.

## TASK 1.1 - Audit MPD vers Doctrine

- [x] Inventorier les entites presentes dans `www/src/Entity`.
- [x] Inventorier les migrations existantes.
- [x] Comparer l'existant au MPD officiel.
- [x] Comparer le MPD avec l'inventaire de donnees du CDC.
- [x] Classer chaque ecart CDC/MPD: `A_IMPLEMENTER_PHASE_1`, `STRUCTURE_SEULE`, `EXTENSION_ULTERIEURE`, `ARBITRAGE_REQUIS`.
- [x] Confirmer la liste des 19 tables MPD.
- [x] Confirmer que `Notification` est hors Phase 1 tant qu'absent du MPD.
- [x] Confirmer que `AdminActionLog` est hors Phase 1 tant qu'absent du MPD.
- [x] Confirmer le statut de `Language` / `UserLanguage` malgre leur absence de l'inventaire CDC page 8.
- [x] Identifier les PK simples.
- [x] Identifier les PK composites.
- [x] Identifier toutes les FK.
- [x] Determiner les cardinalites Doctrine.
- [x] Determiner les types PHP/Doctrine.
- [x] Verifier les nullabilites.
- [x] Documenter les ecarts sans modifier le modele.
- [x] Documenter les champs structurels lies a des extensions futures sans implementer leur logique metier.
- [x] Validation: `MPD_ANALYSIS = APPROVED`.

Tables a confirmer:

- [x] User
- [x] Language
- [x] UserLanguage
- [x] UserStatus
- [x] AgeVerificationStatus
- [x] Country
- [x] Property
- [x] PropertyCategory
- [x] PropertyStatus
- [x] Equipment
- [x] PropertyEquipment
- [x] PropertyImage
- [x] Booking
- [x] BookingStatus
- [x] Price
- [x] FavoriteProperty
- [x] Review
- [x] Conversation
- [x] Message

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

- [x] Creer `Language`.
- [x] Mapper `Language.id`.
- [x] Mapper `Language.label`.
- [x] Prevoir relation avec `UserLanguage`.
- [x] Creer `UserStatus`.
- [x] Mapper `UserStatus.id`.
- [x] Mapper `UserStatus.label`.
- [x] Ajouter fixtures `ACTIVE`, `SUSPENDED`, `DELETED`.
- [x] Creer `AgeVerificationStatus`.
- [x] Mapper `AgeVerificationStatus.id`.
- [x] Mapper `AgeVerificationStatus.label`.
- [x] Ajouter fixtures `PENDING`, `VERIFIED`, `REJECTED`.
- [x] Creer `Country`.
- [x] Mapper `Country.id`.
- [x] Mapper `Country.label`.
- [x] Ajouter contraintes.
- [x] Ajouter relations inverses pertinentes.
- [x] Tester mapping des referentiels.

## TASK 1.3 - Entite User

- [x] Creer ou adopter `User`.
- [x] Implementer `UserInterface`.
- [x] Implementer `PasswordAuthenticatedUserInterface`.
- [x] Mapper `id`.
- [x] Mapper `email`.
- [x] Ajouter contrainte `UNIQUE email`.
- [x] Mapper `password`.
- [x] Mapper `firstname`.
- [x] Mapper `lastname`.
- [x] Mapper `phone`.
- [x] Mapper `avatar`.
- [x] Mapper `bio`.
- [x] Mapper `address`.
- [x] Mapper `city`.
- [x] Mapper `zip_code`.
- [x] Mapper `birth_date`.
- [x] Mapper `email_verified`.
- [x] Mapper `term_accepted_at`.
- [x] Mapper `deleted_at`.
- [x] Mapper `roles`.
- [x] Relation ManyToOne vers `UserStatus`.
- [x] Relation ManyToOne vers `AgeVerificationStatus`.
- [x] Relation ManyToOne vers `Country`.
- [x] Ajouter relation inverse `properties`.
- [x] Ajouter relation inverse `bookings`.
- [x] Ajouter relation inverse `conversations`.
- [x] Ajouter relation inverse `messages envoyes`.
- [x] Ajouter relation inverse `langues`.
- [x] Ajouter relation inverse `favoris`.
- [x] Controler les methodes generees.
- [x] Eviter setter arbitraire du proprietaire de ressources metier.

## TASK 1.4 - Relation UserLanguage

- [x] Creer association explicite `UserLanguage`.
- [x] Mapper PK composite `user_id`, `language_id`.
- [x] Mapper FK vers `User`.
- [x] Mapper FK vers `Language`.
- [x] Empêcher les doublons.
- [x] Ajouter methodes de manipulation cote `User`.
- [x] Tester ajout d'une langue.
- [x] Tester suppression d'une langue.

## TASK 1.5 - Referentiels Logement

- [x] Creer `PropertyCategory`.
- [x] Mapper `PropertyCategory.id`.
- [x] Mapper `PropertyCategory.label`.
- [x] Creer `PropertyStatus`.
- [x] Mapper `PropertyStatus.id`.
- [x] Mapper `PropertyStatus.label`.
- [x] Ajouter fixtures `DRAFT`, `PUBLISHED`, `HIDDEN`.
- [x] Creer `Equipment`.
- [x] Mapper `Equipment.id`.
- [x] Mapper `Equipment.label`.
- [x] Ajouter relations inverses pertinentes.
- [x] Ajouter fixtures de demonstration.
- [x] Tester mapping.

## TASK 1.6 - Entite Property

- [x] Creer `Property`.
- [x] Mapper `id`.
- [x] Mapper `title`.
- [x] Mapper `description`.
- [x] Mapper `max_guest`.
- [x] Mapper `bedrooms`.
- [x] Mapper `bathrooms`.
- [x] Mapper `beds`.
- [x] Mapper `area_m2`.
- [x] Mapper `address`.
- [x] Mapper `city`.
- [x] Mapper `zip_code`.
- [x] Mapper `deposit` en entier.
- [x] Mapper `cleaning_fee` en entier.
- [x] Mapper `review_count`.
- [x] Mapper `average_rating`.
- [x] Mapper `nightly_price` en entier.
- [x] Mapper `published_at`.
- [x] Mapper `weekend_price` en entier.
- [x] Mapper `pets_allowed`.
- [x] Mapper `created_at`.
- [x] Mapper `updated_at`.
- [x] Mapper `deleted_at`.
- [x] Relation proprietaire vers `User`.
- [x] Relation vers `Country`.
- [x] Relation vers `PropertyCategory`.
- [x] Relation vers `PropertyStatus`.
- [x] Initialiser collection `images`.
- [x] Initialiser collection `equipements`.
- [x] Initialiser collection `bookings`.
- [x] Initialiser collection `prices`.
- [x] Initialiser collection `favoris`.
- [x] Initialiser collection `conversations`.
- [x] Gerer `createdAt`.
- [x] Gerer `updatedAt`.
- [x] Gerer `publishedAt`.
- [x] Gerer soft-delete via `deletedAt`.
- [x] Conserver `reviewCount` et `averageRating` sans calcul metier.
- [x] Controler les index futurs.

## TASK 1.7 - Association PropertyEquipment

- [x] Creer association explicite `PropertyEquipment`.
- [x] Mapper PK composite `property_id`, `equipment_id`.
- [x] Mapper FK vers `Property`.
- [x] Mapper FK vers `Equipment`.
- [x] Empêcher les doublons.
- [x] Ajouter methodes add/remove.
- [x] Verifier cascades.
- [x] Garantir qu'un retrait ne supprime pas `Equipment`.

## TASK 1.8 - Entite PropertyImage

- [x] Creer `PropertyImage`.
- [x] Mapper `id`.
- [x] Mapper `image`.
- [x] Mapper `display_order`.
- [x] Mapper `is_main`.
- [x] Mapper relation `property_id`.
- [x] Ajouter collection cote `Property`.
- [x] Ne pas developper l'upload.

## TASK 1.9 - Referentiel BookingStatus

- [x] Creer `BookingStatus`.
- [x] Mapper `id`.
- [x] Mapper `label`.
- [x] Ajouter fixtures `PENDING`, `ACCEPTED`, `REJECTED`, `CANCELLED`.

## TASK 1.10 - Entite Booking

- [x] Creer `Booking`.
- [x] Mapper `id`.
- [x] Mapper `check_in`.
- [x] Mapper `check_out`.
- [x] Mapper `adult_count`.
- [x] Mapper `children_count`.
- [x] Mapper `night_subtotal`.
- [x] Mapper `cleaning_fee`.
- [x] Mapper `deposit`.
- [x] Mapper `total_amount`.
- [x] Mapper `cancellation_reason`.
- [x] Mapper `cancellation_date`.
- [x] Relation vers `BookingStatus`.
- [x] Relation vers `Property`.
- [x] Relation vers `User` voyageur.
- [x] Ajouter collections/inverse mapping.
- [x] Valider `checkOut > checkIn`.
- [x] Valider `adultes >= 1`.
- [x] Valider `enfants >= 0`.
- [x] Valider `montants >= 0`.
- [x] Preparer index de recherche des chevauchements.

## TASK 1.11 - Entite Price

- [x] Creer `Price`.
- [x] Mapper `id`.
- [x] Mapper `day`.
- [x] Mapper `price_night` en entier.
- [x] Mapper `is_block`.
- [x] Relation vers `Property`.
- [x] Ajouter contrainte unique `Property + day`.
- [x] Ajouter index.
- [x] Ne pas developper le calendrier.

## TASK 1.12 - Association FavoriteProperty

- [x] Creer association explicite `FavoriteProperty`.
- [x] Mapper PK composite `user_id`, `property_id`.
- [x] Mapper relation `User`.
- [x] Mapper relation `Property`.
- [x] Empêcher doublon.
- [x] Ajouter mapping inverse.
- [x] Ne pas developper l'UI.

## TASK 1.13 - Entite Review

- [x] Creer `Review`.
- [x] Mapper `id`.
- [x] Mapper `rating` entier.
- [x] Mapper `comment`.
- [x] Mapper `host_reply`.
- [x] Mapper `created_at`.
- [x] Mapper `host_reply_date`.
- [x] Mapper `is_display`.
- [x] Relation vers `Booking`.
- [x] Preparer relation unique `Booking` vers `Review` selon arbitrage metier.

## TASK 1.14 - Entite Conversation

- [x] Creer `Conversation`.
- [x] Mapper `id`.
- [x] Mapper `created_at`.
- [x] Mapper `last_message_at`.
- [x] Relation vers `User`.
- [x] Relation vers `Property`.
- [x] Relation vers `Message`.
- [x] Gerer timestamps.

## TASK 1.15 - Entite Message

- [x] Creer `Message`.
- [x] Mapper `id`.
- [x] Mapper `content`.
- [x] Mapper `read_at`.
- [x] Relation vers `Conversation`.
- [x] Relation vers `User sender`.
- [x] Ajouter collections inverses.

## TASK 1.16 - Contraintes et Index

- [x] Verifier `UNIQUE User.email`.
- [x] Verifier `UNIQUE Price(property_id, day)`.
- [x] Verifier `PK UserLanguage(user_id, language_id)`.
- [x] Verifier `PK PropertyEquipment(property_id, equipment_id)`.
- [x] Verifier `PK FavoriteProperty(user_id, property_id)`.
- [x] Ajouter index `Property(status_id, city)`.
- [x] Ajouter index `Property(user_id, status_id)`.
- [x] Ajouter index `Booking(property_id, status_id, check_in, check_out)`.
- [x] Ajouter index `Booking(user_id, status_id)`.

## TASK 1.17 - Migration Doctrine Complete

- [x] Generer migration.
- [x] Auditer SQL.
- [x] Controler tables.
- [x] Controler colonnes.
- [x] Controler types.
- [x] Controler FK.
- [x] Controler PK composites.
- [x] Controler unique.
- [x] Controler index.
- [x] Controler nullabilite.
- [x] Controler `ON DELETE`.
- [x] Garantir aucune cascade destructive sur historique metier.

## TASK 1.18 - Fixtures Initiales

- [x] Fixtures `Language`.
- [x] Fixtures `Country`.
- [x] Fixtures `UserStatus`.
- [x] Fixtures `AgeVerificationStatus`.
- [x] Fixtures `PropertyCategory`.
- [x] Fixtures `PropertyStatus`.
- [x] Fixtures `Equipment`.
- [x] Fixtures `BookingStatus`.
- [x] Creer deux comptes `USER`.
- [x] Creer un compte `ADMIN`.
- [x] Creer quelques proprietes.
- [x] Creer quelques images.
- [x] Creer reservations de demonstration si utile.

## TASK 1.19 - Validation MPD Finale

- [x] Auditer 100% des tables MPD versus Doctrine.
- [x] Auditer 100% des champs MPD versus Doctrine.
- [x] Auditer 100% des relations MPD versus Doctrine.
- [x] Tester base vide vers migration.
- [x] Charger fixtures.
- [x] Lancer `docker compose exec apache_app_airbnb php bin/console doctrine:schema:validate`.
- [x] Lancer tests pertinents.
- [x] Validation: `MPD_COMPLIANCE = APPROVED`.

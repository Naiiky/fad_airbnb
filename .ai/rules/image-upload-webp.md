# Image Upload & WebP Storage

Cette règle s'applique à toute fonctionnalité du projet acceptant, transformant ou stockant une image utilisateur.

Elle est obligatoire pour tous les agents intervenant sur les uploads, notamment :

- Symfony Developer
- Frontend Developer
- Security Reviewer
- QA Engineer
- Code Reviewer

## 1. Source de vérité

Tout upload d'image applicatif doit utiliser **VichUploaderBundle**.

Il est interdit d'implémenter parallèlement un autre mécanisme d'upload manuel avec :

- `UploadedFile::move()`
- `move_uploaded_file()`
- copie manuelle vers `public/uploads`
- écriture directe du fichier reçu
- autre système d'upload custom contournant VichUploaderBundle

sauf justification architecturale explicitement approuvée.

## 2. Format de stockage obligatoire

Le serveur ne doit conserver les images applicatives que dans le format `WebP`.

Les fichiers sources éventuellement reçus en JPEG, JPG, PNG ou WebP peuvent être utilisés temporairement pendant le traitement mais ne doivent pas être conservés comme fichiers définitifs.

Après traitement réussi, le fichier persistant doit avoir :

- une extension `.webp`
- un contenu réellement encodé en WebP
- un MIME cohérent `image/webp`

Renommer simplement `.jpg` ou `.png` en `.webp` est strictement interdit.

## 3. Pipeline obligatoire

Tout upload d'image doit suivre conceptuellement ce pipeline :

`UploadedFile -> Symfony Validator -> validation MIME / taille -> VichUploaderBundle -> ImageProcessor -> décodage de l'image -> normalisation éventuelle -> redimensionnement si la politique du contexte l'exige -> encodage WebP via GD -> stockage du .webp -> suppression de tout fichier source temporaire devenu inutile -> persistance du nom final dans Doctrine`

Le résultat final doit être un fichier WebP valide.

## 4. Responsabilités

VichUploaderBundle reste responsable du cycle de vie lié à l'upload : réception du fichier, mapping avec l'entité, nommage / stockage selon la configuration retenue, remplacement et suppression liée au cycle de vie de l'entité lorsque configuré.

La transformation d'image doit être centralisée dans un service réutilisable, par exemple `ImageProcessor` ou `WebpImageProcessor`. Le nom final doit suivre les conventions existantes du projet.

Ce service est responsable de décoder l'image source, vérifier qu'elle peut réellement être traitée, préserver correctement la transparence lorsque pertinente, redimensionner lorsque demandé, convertir vers WebP, contrôler la qualité d'encodage, produire le fichier final et libérer les ressources GD.

La logique de conversion ne doit jamais être dupliquée dans Controller, Entity, FormType, Twig ou Listener spécifique à une seule feature.

## 5. GD

Le projet utilise GD pour la conversion WebP.

Avant d'implémenter une feature dépendant de la conversion, vérifier `gd_info()['WebP Support'] === true` ou un contrôle fonctionnel équivalent.

Ne jamais supposer que la présence de l'extension GD implique automatiquement le support WebP.

Si WebP n'est pas disponible, la tâche doit être considérée comme bloquée au niveau infrastructure et non contournée en stockant du JPEG ou du PNG.

## 6. Validation des fichiers

L'extension fournie par le navigateur ne constitue jamais une preuve du type réel du fichier.

Toute image reçue doit être contrôlée côté serveur.

Vérifier notamment :

- taille maximale
- MIME/type réel
- possibilité réelle de décoder l'image
- dimensions si une limite est définie
- format source autorisé

Ne jamais construire une validation de sécurité uniquement à partir de l'extension du fichier, `getClientOriginalName()` ou `getClientOriginalExtension()`.

Le nom original utilisateur ne doit jamais servir de nom de stockage.

## 7. Formats d'entrée

Par défaut, les formats d'entrée autorisés sont JPEG, PNG et WebP.

Un nouveau format, par exemple AVIF ou GIF, ne doit pas être accepté automatiquement. Son support doit être explicitement décidé et testé.

Pour un WebP envoyé en entrée, le fichier peut être réencodé afin d'appliquer la politique commune du projet.

## 8. Nom des fichiers

Les fichiers finaux doivent recevoir un nom non contrôlé par l'utilisateur.

Utiliser une stratégie sûre et non prédictible ou suffisamment unique, par exemple UUID, ULID ou stratégie de nommage Vich dédiée.

Le résultat doit toujours terminer par `.webp`.

Ne jamais conserver directement `photo-ma-maison.jpg` comme nom physique fourni par l'utilisateur.

## 9. Qualité WebP

La qualité d'encodage doit être centralisée et configurable.

Valeur recommandée par défaut pour le projet : `82`.

Ne pas disperser des valeurs arbitraires telles que 70, 80, 90 ou 100 dans différents services.

Une modification de la qualité globale doit pouvoir être réalisée depuis un seul emplacement de configuration.

## 10. Dimensions

Une image ne doit pas être conservée à une résolution inutilement élevée simplement parce que le fichier source possède cette résolution.

Lorsqu'une politique de dimensions existe pour le contexte concerné, le traitement doit conserver le ratio, ne jamais agrandir inutilement une image plus petite, réduire l'image si elle dépasse les dimensions maximales et appliquer le redimensionnement avant l'encodage final WebP.

Les dimensions maximales doivent être configurables et dépendre du type d'image lorsque nécessaire.

Exemple : `PropertyImage` et `User.avatar` peuvent avoir des politiques différentes.

Ne pas coder ces dimensions directement dans les Controllers.

## 11. Transparence

Lors de la conversion PNG/WebP avec transparence :

- préserver le canal alpha lorsque nécessaire
- ne pas générer involontairement un fond noir
- tester explicitement les images transparentes

## 12. Remplacement

Lors du remplacement d'une image :

1. valider le nouveau fichier
2. effectuer la conversion
3. vérifier que le nouveau WebP est valide
4. mettre à jour la ressource
5. supprimer l'ancien fichier selon le cycle Vich configuré

Ne jamais supprimer l'image précédente avant d'avoir obtenu avec succès sa remplaçante.

Un échec de conversion ne doit pas laisser l'entité dans un état incohérent.

## 13. Gestion des erreurs

Une image invalide ou une conversion impossible doit être refusée proprement, produire une erreur utilisateur compréhensible, ne pas exposer d'exception technique brute, ne pas persister un nom de fichier inexistant, ne pas laisser un fichier source permanent et préserver l'image précédente lors d'un remplacement échoué.

## 14. Controllers

Les Controllers doivent rester fins.

Ils ne doivent contenir aucune logique du type :

- `imagecreatefromjpeg()`
- `imagecreatefrompng()`
- `imagewebp()`
- calcul de dimensions
- déplacement physique d'image
- suppression physique manuelle liée au workflow Vich

Ils orchestrent uniquement le formulaire / cas d'usage et laissent l'infrastructure dédiée traiter l'image.

## 15. Entities

Les entités Doctrine ne doivent contenir aucune logique GD ou filesystem.

Elles peuvent contenir une propriété fichier non persistée nécessaire à Vich, un nom de fichier persisté, des métadonnées prévues par le MPD et un timestamp nécessaire au fonctionnement de Vich, mais aucune transformation d'image.

## 16. Doctrine

La base de données stocke uniquement la référence nécessaire au fichier final.

Par exemple : `PropertyImage.image = "0198f25c....webp"`.

Ne jamais stocker les bytes de l'image, le base64 ou le fichier original complet, sauf modification explicite de l'architecture.

## 17. Suppression

La suppression d'une ressource possédant une image doit utiliser le cycle de vie Vich configuré.

Éviter les suppressions filesystem dispersées dans les Controllers.

Le comportement doit être cohérent pour le remplacement, la suppression de l'image et la suppression de l'entité lorsque cette suppression physique est attendue.

## 18. Tests obligatoires

Toute feature d'upload doit couvrir au minimum les cas pertinents suivants :

- Upload JPEG valide : accepté, fichier final `.webp`, fichier final réellement WebP.
- PNG valide : accepté, WebP généré.
- WebP valide : accepté, résultat conforme à la politique commune.
- Faux fichier non-image renommé `.jpg` : refusé.
- MIME interdit : refusé.
- Taille excessive : refusée.
- Conversion impossible : erreur propre, aucune donnée incohérente persistée.
- Remplacement : ancienne image, nouvelle image valide, nouvelle WebP disponible, ancienne supprimée selon le cycle prévu.
- Échec de remplacement : ancienne image, nouvel upload invalide, ancienne image toujours disponible.
- Transparence : si PNG transparent accepté, transparence conservée après WebP.

## 19. Validation finale

Une fonctionnalité d'upload ne peut pas être APPROVED si :

- VichUploaderBundle est contourné
- un original JPEG/PNG est conservé définitivement sans justification
- le fichier possède seulement une extension `.webp` sans encodage réel WebP
- les validations MIME/taille sont absentes
- la conversion est réalisée dans le Controller
- le nom utilisateur sert de nom physique
- les erreurs de conversion peuvent casser l'état de l'entité
- les tests de conversion manquent

## 20. Principe absolu

Pour tout upload d'image dans ce projet :

`VichUploaderBundle -> traitement centralisé -> WebP -> stockage`

Le stockage définitif d'une image applicative en JPEG, JPG ou PNG est interdit.

Si une nouvelle feature nécessite une exception à cette règle, l'agent doit la signaler comme décision architecturale et ne jamais introduire cette exception silencieusement.

<?php

namespace App\Controller;

use App\Entity\Country;
use App\Entity\Equipment;
use App\Entity\Property;
use App\Entity\PropertyCategory;
use App\Entity\PropertyImage;
use App\Entity\PropertyStatus;
use App\Entity\User;
use App\Form\PropertyFormType;
use App\Repository\CountryRepository;
use App\Repository\PropertyCategoryRepository;
use App\Repository\PropertyRepository;
use App\Repository\PropertyStatusRepository;
use App\Security\AgeMajorityChecker;
use App\Security\PropertyVoter;
use App\Upload\PropertyImageUploader;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/host/properties', name: 'app_host_property_')]
#[IsGranted('ROLE_USER')]
class HostPropertyController extends AbstractController
{
    public function __construct(
        private readonly AgeMajorityChecker $ageMajorityChecker,
        private readonly PropertyImageUploader $propertyImageUploader,
    ) {
    }

    #[Route('', name: 'index', methods: ['GET'])]
    public function index(PropertyRepository $propertyRepository): Response
    {
        $user = $this->getHostUser();

        return $this->render('host/property/index.html.twig', [
            'properties' => $propertyRepository->findForOwner($user),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        CountryRepository $countryRepository,
        PropertyCategoryRepository $categoryRepository,
        PropertyStatusRepository $statusRepository,
    ): Response {
        $user = $this->getHostUser();
        $this->denyAccessUnlessAdult($user);

        $draftStatus = $this->getStatus($statusRepository, 'DRAFT');
        $property = new Property(
            $user,
            $this->getDefaultReference($countryRepository, Country::class, 'pays'),
            $this->getDefaultReference($categoryRepository, PropertyCategory::class, 'catégorie'),
            $draftStatus,
            '',
            '',
            '',
            '',
            '',
            1,
        );

        $form = $this->createForm(PropertyFormType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $property->setStatus($draftStatus);
            $this->syncEquipments($property, $this->normalizeEquipments($form->get('equipments')->getData()));
            $uploadedPaths = [];

            try {
                $uploadedPaths = $this->addUploadedImages($property, $form);
                $entityManager->persist($property);
                $entityManager->flush();
            } catch (\RuntimeException $exception) {
                $this->removeStoredImages($uploadedPaths);
                $form->get('images')->addError(new FormError($exception->getMessage()));

                return $this->render('host/property/form.html.twig', [
                    'propertyForm' => $form,
                    'property' => $property,
                    'title' => 'Créer un logement',
                    'submitLabel' => 'Créer le brouillon',
                    'canManageImages' => false,
                ], new Response(Response::HTTP_UNPROCESSABLE_ENTITY));
            }

            $this->addFlash('success', 'Votre logement a été créé en brouillon.');

            return $this->redirectToRoute('app_host_property_index');
        }

        return $this->render('host/property/form.html.twig', [
            'propertyForm' => $form,
            'property' => $property,
            'title' => 'Créer un logement',
            'submitLabel' => 'Créer le brouillon',
            'canManageImages' => false,
        ], new Response($form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Property $property, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(PropertyVoter::EDIT, $property);

        $form = $this->createForm(PropertyFormType::class, $property);
        $form->get('equipments')->setData($this->getSelectedEquipments($property));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->syncEquipments($property, $this->normalizeEquipments($form->get('equipments')->getData()));
            $uploadedPaths = [];

            try {
                $uploadedPaths = $this->addUploadedImages($property, $form);
                $entityManager->flush();
            } catch (\RuntimeException $exception) {
                $this->removeStoredImages($uploadedPaths);
                $form->get('images')->addError(new FormError($exception->getMessage()));

                return $this->render('host/property/form.html.twig', [
                    'propertyForm' => $form,
                    'property' => $property,
                    'title' => 'Modifier le logement',
                    'submitLabel' => 'Enregistrer',
                    'canManageImages' => true,
                ], new Response(Response::HTTP_UNPROCESSABLE_ENTITY));
            }

            $this->addFlash('success', 'Votre logement a été mis à jour.');

            return $this->redirectToRoute('app_host_property_index');
        }

        return $this->render('host/property/form.html.twig', [
            'propertyForm' => $form,
            'property' => $property,
            'title' => 'Modifier le logement',
            'submitLabel' => 'Enregistrer',
            'canManageImages' => true,
        ], new Response($form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    #[Route('/{id}/images/reorder', name: 'images_reorder', methods: ['POST'])]
    public function reorderImages(Request $request, Property $property, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(PropertyVoter::EDIT, $property);
        $this->validateCsrfToken($request, 'reorder_property_images_'.$property->getId());

        $imageIds = $request->request->all('images');
        $property->reorderImages(array_values(array_filter($imageIds, static fn (mixed $imageId): bool => \is_string($imageId) && '' !== $imageId)));
        $entityManager->flush();

        $this->addFlash('success', "L'ordre des photos a ete mis a jour.");

        return $this->redirectToRoute('app_host_property_edit', ['id' => $property->getId()]);
    }

    #[Route('/{id}/images/{imageId}/cover', name: 'images_cover', methods: ['POST'])]
    public function setCoverImage(Request $request, Property $property, string $imageId, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(PropertyVoter::EDIT, $property);
        $this->validateCsrfToken($request, 'cover_property_image_'.$imageId);

        $property->setMainImage($this->getPropertyImage($property, $imageId));
        $entityManager->flush();

        $this->addFlash('success', 'La photo de couverture a ete mise a jour.');

        return $this->redirectToRoute('app_host_property_edit', ['id' => $property->getId()]);
    }

    #[Route('/{id}/images/{imageId}/delete', name: 'images_delete', methods: ['POST'])]
    public function deleteImage(Request $request, Property $property, string $imageId, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted(PropertyVoter::EDIT, $property);
        $this->validateCsrfToken($request, 'delete_property_image_'.$imageId);

        $image = $this->getPropertyImage($property, $imageId);
        $storedPath = $image->getImage();
        $property->removeImage($image);
        $entityManager->flush();
        $this->propertyImageUploader->remove($storedPath);

        $this->addFlash('success', 'La photo a ete supprimee.');

        return $this->redirectToRoute('app_host_property_edit', ['id' => $property->getId()]);
    }

    #[Route('/{id}/publish', name: 'publish', methods: ['POST'])]
    public function publish(Request $request, Property $property, EntityManagerInterface $entityManager, PropertyStatusRepository $statusRepository): Response
    {
        $this->denyAccessUnlessGranted(PropertyVoter::PUBLISH, $property);
        $this->denyAccessUnlessAdult($property->getUser());
        $this->validateCsrfToken($request, 'publish_property_'.$property->getId());

        if ('DRAFT' !== $property->getStatus()->getLabel()) {
            $this->addFlash('notice', 'Seuls les brouillons peuvent être publiés.');

            return $this->redirectToRoute('app_host_property_index');
        }

        if (!$this->isComplete($property)) {
            $this->addFlash('notice', 'Complétez les informations obligatoires avant de publier.');

            return $this->redirectToRoute('app_host_property_edit', ['id' => $property->getId()]);
        }

        $property
            ->setStatus($this->getStatus($statusRepository, 'PUBLISHED'))
            ->publish(new \DateTimeImmutable());
        $entityManager->flush();

        $this->addFlash('success', 'Votre logement est publié.');

        return $this->redirectToRoute('app_host_property_index');
    }

    #[Route('/{id}/hide', name: 'hide', methods: ['POST'])]
    public function hide(Request $request, Property $property, EntityManagerInterface $entityManager, PropertyStatusRepository $statusRepository): Response
    {
        $this->denyAccessUnlessGranted(PropertyVoter::HIDE, $property);
        $this->validateCsrfToken($request, 'hide_property_'.$property->getId());

        $property->setStatus($this->getStatus($statusRepository, 'HIDDEN'));
        $entityManager->flush();

        $this->addFlash('success', 'Votre logement est masqué.');

        return $this->redirectToRoute('app_host_property_index');
    }

    private function getHostUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return $user;
    }

    private function denyAccessUnlessAdult(User $user): void
    {
        $birthDate = $user->getBirthDate();
        if (null === $birthDate || !$this->ageMajorityChecker->isAdult($birthDate)) {
            throw $this->createAccessDeniedException('Vous devez être majeur pour gérer un logement.');
        }
    }

    private function validateCsrfToken(Request $request, string $tokenId): void
    {
        if (!$this->isCsrfTokenValid($tokenId, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
    }

    private function getStatus(PropertyStatusRepository $statusRepository, string $label): PropertyStatus
    {
        $status = $statusRepository->findOneBy(['label' => $label]);
        if (!$status instanceof PropertyStatus) {
            throw new \RuntimeException(sprintf('Le statut de logement "%s" est introuvable.', $label));
        }

        return $status;
    }

    /**
     * @template T of object
     *
     * @param object $repository
     * @param class-string<T> $className
     *
     * @return T
     */
    private function getDefaultReference(object $repository, string $className, string $label): object
    {
        if (!method_exists($repository, 'findOneBy')) {
            throw new \RuntimeException(sprintf('Le référentiel %s est indisponible.', $label));
        }

        $reference = $repository->findOneBy([]);
        if (!$reference instanceof $className) {
            throw new \RuntimeException(sprintf('Aucune %s disponible pour créer un logement.', $label));
        }

        return $reference;
    }

    /** @return list<Equipment> */
    private function getSelectedEquipments(Property $property): array
    {
        return $property->getPropertyEquipments()
            ->map(static fn ($propertyEquipment) => $propertyEquipment->getEquipment())
            ->toArray();
    }

    /**
     * @param mixed $equipments
     *
     * @return list<Equipment>
     */
    private function normalizeEquipments(mixed $equipments): array
    {
        if ($equipments instanceof Collection) {
            $equipments = $equipments->toArray();
        }

        return \is_array($equipments) ? array_values(array_filter($equipments, static fn (mixed $equipment): bool => $equipment instanceof Equipment)) : [];
    }

    /** @param list<Equipment> $selectedEquipments */
    private function syncEquipments(Property $property, array $selectedEquipments): void
    {
        foreach ($property->getPropertyEquipments()->toArray() as $propertyEquipment) {
            if (!\in_array($propertyEquipment->getEquipment(), $selectedEquipments, true)) {
                $property->removeEquipment($propertyEquipment->getEquipment());
            }
        }

        foreach ($selectedEquipments as $equipment) {
            $property->addEquipment($equipment);
        }
    }

    /**
     * @param FormInterface<Property> $form
     *
     * @return list<string>
     */
    private function addUploadedImages(Property $property, FormInterface $form): array
    {
        $uploadedPaths = [];
        $files = $form->get('images')->getData();
        if (!\is_array($files)) {
            return $uploadedPaths;
        }

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $storedPath = $this->propertyImageUploader->upload($property, $file);
            $uploadedPaths[] = $storedPath;
            $property->addImage($storedPath);
        }

        return $uploadedPaths;
    }

    /** @param list<string> $storedPaths */
    private function removeStoredImages(array $storedPaths): void
    {
        foreach ($storedPaths as $storedPath) {
            $this->propertyImageUploader->remove($storedPath);
        }
    }

    private function getPropertyImage(Property $property, string $imageId): PropertyImage
    {
        foreach ($property->getImages() as $image) {
            if ($image->getId() === $imageId) {
                return $image;
            }
        }

        throw $this->createNotFoundException('Photo introuvable.');
    }

    private function isComplete(Property $property): bool
    {
        return '' !== trim($property->getTitle())
            && '' !== trim($property->getDescription())
            && '' !== trim($property->getAddress())
            && '' !== trim($property->getCity())
            && '' !== trim($property->getZipCode())
            && $property->getMaxGuest() > 0
            && $property->getBathrooms() > 0
            && $property->getBeds() > 0
            && $property->getAreaM2() > 0
            && $property->getNightlyPrice() > 0
            && $property->getWeekendPrice() > 0
            && $property->getCleaningFee() >= 0
            && $property->getDeposit() >= 0;
    }
}

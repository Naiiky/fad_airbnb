<?php

namespace App\Controller;

use App\Booking\BookingRequestCreator;
use App\Catalogue\PropertyCatalogueSearch;
use App\Entity\Booking;
use App\Entity\Property;
use App\Entity\User;
use App\Form\BookingFormType;
use App\Repository\PropertyCategoryRepository;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(PropertyRepository $propertyRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'properties' => $propertyRepository->findLatestPublished(),
        ]);
    }

    #[Route('/catalogue', name: 'app_catalogue', methods: ['GET'])]
    public function catalogue(
        Request $request,
        PropertyRepository $propertyRepository,
        PropertyCategoryRepository $categoryRepository,
    ): Response {
        $search = PropertyCatalogueSearch::fromQuery(
            $request->query->get('ville'),
            $request->query->get('categorie'),
            $request->query->get('page'),
        );
        $pagination = $propertyRepository->findPublishedCatalogue($search);

        return $this->render('home/catalogue.html.twig', [
            'search' => $search,
            'pagination' => $pagination,
            'categories' => $categoryRepository->findBy([], ['label' => 'ASC']),
        ]);
    }

    #[Route('/properties/{id}', name: 'app_property_show', methods: ['GET', 'POST'])]
    public function show(
        string $id,
        Request $request,
        PropertyRepository $propertyRepository,
        BookingRequestCreator $bookingRequestCreator,
        EntityManagerInterface $entityManager,
    ): Response {
        $property = $propertyRepository->findPublishedDetail($id);
        if (!$property instanceof Property) {
            throw $this->createNotFoundException('Logement introuvable.');
        }

        $form = $this->createForm(BookingFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $this->getUser();
            if (!$user instanceof User) {
                $this->addFlash('notice', 'Connectez-vous pour demander une reservation.');

                return $this->redirectToRoute('app_login');
            }

            if ($form->isValid()) {
                $checkIn = $this->toDateImmutable($form->get('checkIn')->getData());
                $checkOut = $this->toDateImmutable($form->get('checkOut')->getData());

                if (null === $checkIn || null === $checkOut) {
                    $form->addError(new FormError('Veuillez choisir des dates valides.'));
                } else {
                    try {
                        $booking = $this->createBookingRequest(
                            $bookingRequestCreator,
                            $property,
                            $user,
                            $checkIn,
                            $checkOut,
                            (int) $form->get('adultCount')->getData(),
                            (int) $form->get('childrenCount')->getData(),
                        );
                    } catch (\InvalidArgumentException $exception) {
                        $form->addError(new FormError($exception->getMessage()));
                    }
                }

                if (isset($booking) && $booking instanceof Booking) {
                    $entityManager->persist($booking);
                    $entityManager->flush();

                    $this->addFlash('success', 'Votre demande de reservation a ete envoyee.');

                    return $this->redirectToRoute('app_property_show', ['id' => $property->getId()]);
                }
            }
        }

        return $this->render('home/property_show.html.twig', [
            'property' => $property,
            'bookingForm' => $form,
        ], new Response($form->isSubmitted() ? Response::HTTP_UNPROCESSABLE_ENTITY : Response::HTTP_OK));
    }

    private function createBookingRequest(
        BookingRequestCreator $bookingRequestCreator,
        Property $property,
        User $user,
        \DateTimeImmutable $checkIn,
        \DateTimeImmutable $checkOut,
        int $adultCount,
        int $childrenCount,
    ): ?Booking {
        return $bookingRequestCreator->create($property, $user, $checkIn, $checkOut, $adultCount, $childrenCount);
    }

    private function toDateImmutable(mixed $date): ?\DateTimeImmutable
    {
        if ($date instanceof \DateTimeImmutable) {
            return $date;
        }

        if ($date instanceof \DateTimeInterface) {
            return \DateTimeImmutable::createFromInterface($date);
        }

        return null;
    }

}

<?php

namespace App\Controller;

use App\Booking\BookingWorkflow;
use App\Entity\Booking;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Security\BookingVoter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/traveler/bookings', name: 'app_traveler_booking_')]
#[IsGranted('ROLE_USER')]
class TravelerBookingController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('traveler/booking/index.html.twig', [
            'bookings' => $bookingRepository->findForTraveler($this->getTravelerUser()),
        ]);
    }

    #[Route('/{id}/cancel', name: 'cancel', methods: ['POST'])]
    public function cancel(Request $request, Booking $booking, BookingWorkflow $bookingWorkflow): Response
    {
        $this->denyAccessUnlessGranted(BookingVoter::CANCEL, $booking);
        $this->validateCsrfToken($request, 'cancel_booking_'.$booking->getId());

        try {
            $bookingWorkflow->cancel($booking, $this->getTravelerUser(), $request->request->getString('reason'));
            $this->addFlash('success', 'Votre reservation a ete annulee.');
        } catch (\InvalidArgumentException $exception) {
            $this->addFlash('notice', $exception->getMessage());
        }

        return $this->redirectToRoute('app_traveler_booking_index');
    }

    private function getTravelerUser(): User
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw new AccessDeniedException();
        }

        return $user;
    }

    private function validateCsrfToken(Request $request, string $tokenId): void
    {
        if (!$this->isCsrfTokenValid($tokenId, (string) $request->request->get('_token'))) {
            throw $this->createAccessDeniedException('Token CSRF invalide.');
        }
    }
}

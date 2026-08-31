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

#[Route('/host/bookings', name: 'app_host_booking_')]
#[IsGranted('ROLE_USER')]
class HostBookingController extends AbstractController
{
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(Request $request, BookingRepository $bookingRepository): Response
    {
        $status = $request->query->getString('status');

        return $this->render('host/booking/index.html.twig', [
            'bookings' => $bookingRepository->findForHost($this->getHostUser(), $status ?: null),
            'current_status' => $status,
        ]);
    }

    #[Route('/{id}/accept', name: 'accept', methods: ['POST'])]
    public function accept(Request $request, Booking $booking, BookingWorkflow $bookingWorkflow): Response
    {
        $this->denyAccessUnlessGranted(BookingVoter::HOST_MANAGE, $booking);
        $this->validateCsrfToken($request, 'accept_booking_'.$booking->getId());

        try {
            $bookingWorkflow->accept($booking);
            $this->addFlash('success', 'La demande de reservation a ete acceptee.');
        } catch (\InvalidArgumentException $exception) {
            $this->addFlash('notice', $exception->getMessage());
        }

        return $this->redirectToRoute('app_host_booking_index');
    }

    #[Route('/{id}/reject', name: 'reject', methods: ['POST'])]
    public function reject(Request $request, Booking $booking, BookingWorkflow $bookingWorkflow): Response
    {
        $this->denyAccessUnlessGranted(BookingVoter::HOST_MANAGE, $booking);
        $this->validateCsrfToken($request, 'reject_booking_'.$booking->getId());

        try {
            $bookingWorkflow->reject($booking);
            $this->addFlash('success', 'La demande de reservation a ete refusee.');
        } catch (\InvalidArgumentException $exception) {
            $this->addFlash('notice', $exception->getMessage());
        }

        return $this->redirectToRoute('app_host_booking_index');
    }

    #[Route('/{id}/cancel', name: 'cancel', methods: ['POST'])]
    public function cancel(Request $request, Booking $booking, BookingWorkflow $bookingWorkflow): Response
    {
        $this->denyAccessUnlessGranted(BookingVoter::CANCEL, $booking);
        $this->validateCsrfToken($request, 'cancel_booking_'.$booking->getId());

        try {
            $bookingWorkflow->cancel($booking, $this->getHostUser(), $request->request->getString('reason'));
            $this->addFlash('success', 'La reservation a ete annulee.');
        } catch (\InvalidArgumentException $exception) {
            $this->addFlash('notice', $exception->getMessage());
        }

        return $this->redirectToRoute('app_host_booking_index');
    }

    private function getHostUser(): User
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

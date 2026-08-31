<?php

namespace App\Booking;

use App\Entity\Booking;
use App\Entity\BookingStatus;
use App\Entity\User;
use App\Repository\BookingRepository;
use App\Repository\BookingStatusRepository;
use Doctrine\ORM\EntityManagerInterface;

class BookingWorkflow
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly BookingRepository $bookingRepository,
        private readonly BookingStatusRepository $statusRepository,
    ) {
    }

    public function accept(Booking $booking): void
    {
        if ('PENDING' !== $booking->getStatus()->getLabel()) {
            throw new \InvalidArgumentException('Seules les demandes en attente peuvent etre acceptees.');
        }

        $this->entityManager->wrapInTransaction(function () use ($booking): void {
            if ($this->bookingRepository->hasAcceptedOverlapExcluding($booking)) {
                throw new \InvalidArgumentException('Une reservation acceptee existe deja sur ces dates.');
            }

            $booking->setStatus($this->getStatus('ACCEPTED'));
        });
    }

    public function reject(Booking $booking): void
    {
        if ('PENDING' !== $booking->getStatus()->getLabel()) {
            throw new \InvalidArgumentException('Seules les demandes en attente peuvent etre refusees.');
        }

        $booking->setStatus($this->getStatus('REJECTED'));
        $this->entityManager->flush();
    }

    public function cancel(Booking $booking, User $actor, ?string $reason = null): void
    {
        $statusLabel = $booking->getStatus()->getLabel();
        if (!\in_array($statusLabel, ['PENDING', 'ACCEPTED'], true)) {
            throw new \InvalidArgumentException('Cette reservation ne peut plus etre annulee.');
        }

        if ($booking->getCheckIn() <= new \DateTimeImmutable('today')) {
            throw new \InvalidArgumentException('Une reservation commencee ne peut plus etre annulee.');
        }

        $defaultReason = $booking->getUser()->getId() === $actor->getId()
            ? 'Annulee par le voyageur'
            : 'Annulee par l hote';

        $booking
            ->setStatus($this->getStatus('CANCELLED'))
            ->cancel(trim((string) $reason) ?: $defaultReason, new \DateTimeImmutable());

        $this->entityManager->flush();
    }

    private function getStatus(string $label): BookingStatus
    {
        $status = $this->statusRepository->findOneBy(['label' => $label]);
        if (!$status instanceof BookingStatus) {
            throw new \RuntimeException(sprintf('Le statut de reservation "%s" est introuvable.', $label));
        }

        return $status;
    }
}

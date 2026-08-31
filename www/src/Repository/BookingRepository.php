<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Property;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    public function hasAcceptedOverlap(Property $property, \DateTimeImmutable $checkIn, \DateTimeImmutable $checkOut): bool
    {
        $count = (int) $this->createQueryBuilder('booking')
            ->select('COUNT(booking.id)')
            ->join('booking.status', 'status')
            ->andWhere('booking.property = :property')
            ->andWhere('status.label = :acceptedStatus')
            ->andWhere('booking.checkIn < :checkOut')
            ->andWhere('booking.checkOut > :checkIn')
            ->setParameter('property', $property)
            ->setParameter('acceptedStatus', 'ACCEPTED')
            ->setParameter('checkIn', $checkIn)
            ->setParameter('checkOut', $checkOut)
            ->getQuery()
            ->getSingleScalarResult();

        return $count > 0;
    }

    public function isAvailable(Property $property, \DateTimeImmutable $checkIn, \DateTimeImmutable $checkOut): bool
    {
        return !$this->hasAcceptedOverlap($property, $checkIn, $checkOut);
    }

    public function hasAcceptedOverlapExcluding(Booking $booking): bool
    {
        $queryBuilder = $this->createQueryBuilder('overlap')
            ->select('COUNT(overlap.id)')
            ->join('overlap.status', 'status')
            ->andWhere('overlap.property = :property')
            ->andWhere('status.label = :acceptedStatus')
            ->andWhere('overlap.checkIn < :checkOut')
            ->andWhere('overlap.checkOut > :checkIn')
            ->setParameter('property', $booking->getProperty())
            ->setParameter('acceptedStatus', 'ACCEPTED')
            ->setParameter('checkIn', $booking->getCheckIn())
            ->setParameter('checkOut', $booking->getCheckOut());

        if (null !== $booking->getId()) {
            $queryBuilder
                ->andWhere('overlap.id != :bookingId')
                ->setParameter('bookingId', $booking->getId());
        }

        return ((int) $queryBuilder->getQuery()->getSingleScalarResult()) > 0;
    }

    /** @return list<Booking> */
    public function findForHost(User $host, ?string $status = null): array
    {
        $queryBuilder = $this->createQueryBuilder('booking')
            ->addSelect('property', 'traveler', 'status', 'images')
            ->join('booking.property', 'property')
            ->join('booking.user', 'traveler')
            ->join('booking.status', 'status')
            ->leftJoin('property.images', 'images')
            ->andWhere('property.user = :host')
            ->andWhere('property.deletedAt IS NULL')
            ->setParameter('host', $host)
            ->orderBy('booking.checkIn', 'ASC')
            ->addOrderBy('images.displayOrder', 'ASC');

        if (null !== $status && '' !== $status) {
            $queryBuilder
                ->andWhere('status.label = :status')
                ->setParameter('status', $status);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /** @return list<Booking> */
    public function findForTraveler(User $traveler): array
    {
        return $this->createQueryBuilder('booking')
            ->addSelect('property', 'host', 'status', 'images')
            ->join('booking.property', 'property')
            ->join('property.user', 'host')
            ->join('booking.status', 'status')
            ->leftJoin('property.images', 'images')
            ->andWhere('booking.user = :traveler')
            ->andWhere('property.deletedAt IS NULL')
            ->setParameter('traveler', $traveler)
            ->orderBy('booking.checkIn', 'ASC')
            ->addOrderBy('images.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function countPendingForHost(User $host): int
    {
        return (int) $this->createQueryBuilder('booking')
            ->select('COUNT(booking.id)')
            ->join('booking.property', 'property')
            ->join('booking.status', 'status')
            ->andWhere('property.user = :host')
            ->andWhere('status.label = :status')
            ->setParameter('host', $host)
            ->setParameter('status', 'PENDING')
            ->getQuery()
            ->getSingleScalarResult();
    }
}

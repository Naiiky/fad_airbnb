<?php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Property;
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
}

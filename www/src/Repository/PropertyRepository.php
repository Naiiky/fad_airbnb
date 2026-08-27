<?php

namespace App\Repository;

use App\Entity\Property;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Property::class);
    }

    /** @return list<Property> */
    public function findForOwner(User $owner): array
    {
        return $this->createQueryBuilder('property')
            ->addSelect('status', 'category', 'country')
            ->join('property.status', 'status')
            ->join('property.category', 'category')
            ->join('property.country', 'country')
            ->andWhere('property.user = :owner')
            ->andWhere('property.deletedAt IS NULL')
            ->setParameter('owner', $owner)
            ->orderBy('property.updatedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\FavoriteProperty;
use App\Entity\Property;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class FavoritePropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteProperty::class);
    }

    public function findOneForUserAndProperty(User $user, Property $property): ?FavoriteProperty
    {
        return $this->findOneBy([
            'user' => $user,
            'property' => $property,
        ]);
    }

    /** @return list<FavoriteProperty> */
    public function findForUser(User $user): array
    {
        return $this->createQueryBuilder('favorite')
            ->addSelect('property', 'status', 'category', 'country', 'images')
            ->join('favorite.property', 'property')
            ->join('property.status', 'status')
            ->join('property.category', 'category')
            ->join('property.country', 'country')
            ->leftJoin('property.images', 'images')
            ->andWhere('favorite.user = :user')
            ->andWhere('status.label = :publishedStatus')
            ->andWhere('property.deletedAt IS NULL')
            ->setParameter('user', $user)
            ->setParameter('publishedStatus', 'PUBLISHED')
            ->orderBy('property.publishedAt', 'DESC')
            ->addOrderBy('images.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

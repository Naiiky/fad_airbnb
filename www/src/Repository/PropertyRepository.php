<?php

namespace App\Repository;

use App\Catalogue\PropertyCatalogueSearch;
use App\Entity\Property;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
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
            ->addSelect('status', 'category', 'country', 'images')
            ->join('property.status', 'status')
            ->join('property.category', 'category')
            ->join('property.country', 'country')
            ->leftJoin('property.images', 'images')
            ->andWhere('property.user = :owner')
            ->andWhere('property.deletedAt IS NULL')
            ->setParameter('owner', $owner)
            ->orderBy('property.updatedAt', 'DESC')
            ->addOrderBy('images.displayOrder', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /** @return list<Property> */
    public function findForAdmin(?string $status = null): array
    {
        $queryBuilder = $this->createQueryBuilder('property')
            ->addSelect('owner', 'status', 'category', 'country', 'images')
            ->join('property.user', 'owner')
            ->join('property.status', 'status')
            ->join('property.category', 'category')
            ->join('property.country', 'country')
            ->leftJoin('property.images', 'images')
            ->andWhere('property.deletedAt IS NULL')
            ->orderBy('property.updatedAt', 'DESC')
            ->addOrderBy('images.displayOrder', 'ASC');

        if (null !== $status && '' !== $status) {
            $queryBuilder
                ->andWhere('status.label = :status')
                ->setParameter('status', $status);
        }

        return $queryBuilder->getQuery()->getResult();
    }

    /** @return list<Property> */
    public function findLatestPublished(int $limit = 6): array
    {
        return $this->createPublishedQueryBuilder()
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findPublishedDetail(string $id): ?Property
    {
        return $this->createPublishedQueryBuilder()
            ->addSelect('owner', 'propertyEquipments', 'equipment')
            ->join('property.user', 'owner')
            ->leftJoin('property.propertyEquipments', 'propertyEquipments')
            ->leftJoin('propertyEquipments.equipment', 'equipment')
            ->andWhere('property.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return array{items: list<Property>, total: int, page: int, perPage: int, totalPages: int}
     */
    public function findPublishedCatalogue(PropertyCatalogueSearch $search): array
    {
        $queryBuilder = $this->createPublishedQueryBuilder();

        if (null !== $search->getCity()) {
            $queryBuilder
                ->andWhere('LOWER(property.city) LIKE :city')
                ->setParameter('city', '%'.strtolower($search->getCity()).'%');
        }

        if (null !== $search->getCategoryId()) {
            $queryBuilder
                ->andWhere('category.id = :categoryId')
                ->setParameter('categoryId', $search->getCategoryId());
        }

        $countQuery = clone $queryBuilder;
        $total = \count(new Paginator($countQuery->getQuery(), true));
        $totalPages = max(1, (int) ceil($total / $search->getPerPage()));
        $page = min($search->getPage(), $totalPages);

        $query = $queryBuilder
            ->setFirstResult(($page - 1) * $search->getPerPage())
            ->setMaxResults($search->getPerPage())
            ->getQuery();

        $paginator = new Paginator($query, true);

        return [
            'items' => iterator_to_array($paginator->getIterator(), false),
            'total' => $total,
            'page' => $page,
            'perPage' => $search->getPerPage(),
            'totalPages' => $totalPages,
        ];
    }

    private function createPublishedQueryBuilder(): \Doctrine\ORM\QueryBuilder
    {
        return $this->createQueryBuilder('property')
            ->addSelect('status', 'category', 'country', 'images')
            ->join('property.status', 'status')
            ->join('property.category', 'category')
            ->join('property.country', 'country')
            ->leftJoin('property.images', 'images')
            ->andWhere('status.label = :publishedStatus')
            ->andWhere('property.deletedAt IS NULL')
            ->setParameter('publishedStatus', 'PUBLISHED')
            ->orderBy('property.publishedAt', 'DESC')
            ->addOrderBy('property.updatedAt', 'DESC')
            ->addOrderBy('images.displayOrder', 'ASC');
    }
}

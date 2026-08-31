<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return array{items: list<User>, total: int, page: int, perPage: int, totalPages: int}
     */
    public function findForAdmin(?string $query, int $page = 1, int $perPage = 20): array
    {
        $page = max(1, $page);
        $perPage = max(1, $perPage);
        $queryBuilder = $this->createQueryBuilder('user')
            ->addSelect('status', 'country')
            ->join('user.status', 'status')
            ->join('user.country', 'country')
            ->orderBy('user.lastname', 'ASC')
            ->addOrderBy('user.firstname', 'ASC');

        if (null !== $query && '' !== trim($query)) {
            $queryBuilder
                ->andWhere('LOWER(user.email) LIKE :query OR LOWER(user.firstname) LIKE :query OR LOWER(user.lastname) LIKE :query')
                ->setParameter('query', '%'.strtolower(trim($query)).'%');
        }

        $countQuery = clone $queryBuilder;
        $total = \count(new Paginator($countQuery->getQuery(), true));
        $totalPages = max(1, (int) ceil($total / $perPage));
        $page = min($page, $totalPages);

        $paginator = new Paginator(
            $queryBuilder
                ->setFirstResult(($page - 1) * $perPage)
                ->setMaxResults($perPage)
                ->getQuery(),
            true,
        );

        return [
            'items' => iterator_to_array($paginator->getIterator(), false),
            'total' => $total,
            'page' => $page,
            'perPage' => $perPage,
            'totalPages' => $totalPages,
        ];
    }
}

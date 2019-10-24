<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getPaginator(string $search): Pagerfanta
    {
        $qb = $this->createQueryBuilder('o');
        if ($search) {
            $qb->andWhere('o.firstName LIKE :search OR o.lastName LIKE :search')->setParameter('search', $search.'%');
        }
        $adapter = new DoctrineORMAdapter($qb);

        return new Pagerfanta($adapter);
    }
}

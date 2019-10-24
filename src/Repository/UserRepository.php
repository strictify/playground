<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Filter\FilterInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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

    public function paginate(FilterInterface $filter): Pagerfanta
    {
        $search = (string)$filter->get('search');

        return $this->getPaginator($filter->getPage(), $search);
    }

    protected function getPaginator(int $page, string $search): Pagerfanta
    {
        $criteria = Criteria::create()->where(Criteria::expr()->startsWith('firstName', $search));

        $qb = $this->createQueryBuilder('o');
        $qb->addCriteria($criteria);

        $adapter = new DoctrineORMAdapter($qb);

        $pager = new Pagerfanta($adapter);
        $pager->setNormalizeOutOfRangePages(true);
        $pager->setCurrentPage($page);

        return $pager;
    }
}

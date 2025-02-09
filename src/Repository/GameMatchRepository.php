<?php

namespace App\Repository;

use App\Entity\GameMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GameMatch>
 */
class GameMatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GameMatch::class);
    }

    public function findMatchesByDateRange(\DateTime $start, \DateTime $end): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.date BETWEEN :start AND :end')
            ->setParameter('start', $start)
            ->setParameter('end', $end)
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

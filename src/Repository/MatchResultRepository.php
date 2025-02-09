<?php

namespace App\Repository;

use App\Entity\MatchResult;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MatchResultRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MatchResult::class);
    }

    public function findByMatchId(int $matchId): array
    {
        return $this->createQueryBuilder('mr')
            ->where('mr.match = :matchId')
            ->setParameter('matchId', $matchId)
            ->getQuery()
            ->getResult();
    }

    public function findByPlayerId(int $playerId): array
    {
        return $this->createQueryBuilder('mr')
            ->where('mr.player = :playerId')
            ->setParameter('playerId', $playerId)
            ->getQuery()
            ->getResult();
    }
}

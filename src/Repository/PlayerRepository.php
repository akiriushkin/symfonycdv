<?php

namespace App\Repository;

use App\Entity\Player;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Player>
 */
class PlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Player::class);
    }


    public function findByName(string $name): ?Player
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function findTopPlayers(int $limit = 10): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.score', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}

<?php

namespace App\Entity;

use App\Repository\MatchResultRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MatchResultRepository::class)]
#[ORM\Table(name: "match_results")]
class MatchResult
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: GameMatch::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private GameMatch $match;

    #[ORM\ManyToOne(targetEntity: Player::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private Player $player;

    #[ORM\Column(type: "integer")]
    private int $kills;

    #[ORM\Column(type: "integer")]
    private int $deaths;

    #[ORM\Column(type: "integer")]
    private int $score;

    #[ORM\Column(type: "integer")]
    private int $rank;

    public function __construct(GameMatch $match, Player $player, int $kills, int $deaths, int $score, int $rank)
    {
        $this->match = $match;
        $this->player = $player;
        $this->kills = $kills;
        $this->deaths = $deaths;
        $this->score = $score;
        $this->rank = $rank;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatch(): GameMatch
    {
        return $this->match;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getKills(): int
    {
        return $this->kills;
    }

    public function getDeaths(): int
    {
        return $this->deaths;
    }

    public function getScore(): int
    {
        return $this->score;
    }

    public function getRank(): int
    {
        return $this->rank;
    }
}

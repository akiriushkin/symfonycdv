<?php

namespace App\Entity;

use App\Repository\GameMatchRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameMatchRepository::class)]
#[ORM\Table(name: "matches")]
class GameMatch
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "datetime")]
    private \DateTimeInterface $date;

    #[ORM\Column(type: "integer")]
    private int $duration; // Время в минутах

    #[ORM\Column(type: "string", length: 50)]
    private string $gameMode;

    public function __construct(string $gameMode, int $duration)
    {
        $this->date = new \DateTime();
        $this->gameMode = $gameMode;
        $this->duration = $duration;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    public function getDuration(): int
    {
        return $this->duration;
    }

    public function getGameMode(): string
    {
        return $this->gameMode;
    }
}

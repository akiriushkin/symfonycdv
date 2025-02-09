<?php

namespace App\Controller;

use App\Entity\Player;
use App\Repository\PlayerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PlayerController extends AbstractController
{
    #[Route('/players', name: 'player_list', methods: ['GET'])]
    public function index(PlayerRepository $playerRepository): JsonResponse
    {
        $players = $playerRepository->findAll();

        $data = [];

        foreach ($players as $player) {
            $data[] = [
                'id' => $player->getId(),
                'name' => $player->getUsername(),
                'score' => $player->getScore(),
            ];
        }

        return $this->json($data);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/players', name: 'player_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['name'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $player = new Player($data['name']);
        $player->setScore(0);
        $em->persist($player);
        $em->flush();

        return $this->json(['message' => 'Player created', 'playerId' => $player->getId()]);
    }
}

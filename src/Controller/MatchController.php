<?php

namespace App\Controller;

use App\Entity\GameMatch;
use App\Repository\GameMatchRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MatchController extends AbstractController
{
    #[Route('/matches', name: 'match_list', methods: ['GET'])]
    public function index(GameMatchRepository $matchRepository): JsonResponse
    {
        $matches = $matchRepository->findAll();
        $data = [];

        foreach ($matches as $match) {
            $data[] = [
                'id' => $match->getId(),
                'date' => $match->getDate()->format('Y-m-d H:i:s'),
                'duration' => $match->getDuration(),
                'gameMode' => $match->getGameMode(),
            ];
        }

        return $this->json($data);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/matches', name: 'match_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['gameMode'], $data['duration'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $match = new GameMatch($data['gameMode'], $data['duration']);
        $em->persist($match);
        $em->flush();

        return $this->json(['message' => 'Match created', 'matchId' => $match->getId()]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/matches', name: 'match_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $em, GameMatchRepository $gameMatchRepository): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['matchId'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $match = $gameMatchRepository->find($data['matchId']);
        if (!$match) {
            return $this->json(['error' => 'Match not found'], 404);
        }

        try {
            $em->remove($match);
            $em->flush();
        } catch (\Exception $e) {
            return $this->json(['error' => 'Unexpected error', 'details' => $e->getMessage()], 500);
        }

        return $this->json(['message' => 'Match deleted']);
    }
}

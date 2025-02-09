<?php

namespace App\Controller;

use App\Entity\GameMatch;
use App\Entity\Player;
use App\Entity\MatchResult;
use App\Repository\MatchResultRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MatchResultController extends AbstractController
{
    #[Route('/match-results', name: 'match_result_list', methods: ['GET'])]
    public function index(MatchResultRepository $matchResultRepository): JsonResponse
    {
        $results = $matchResultRepository->findAll();
        $data = [];

        foreach ($results as $result) {
            $data[] = [
                'id' => $result->getId(),
                'matchId' => $result->getMatch(),
                'winner' => $result->getPlayer(),
                'kills' => $result->getKills(),
                'deaths' => $result->getDeaths(),
                'score' => $result->getScore(),
                'rank' => $result->getRank(),
            ];
        }

        return $this->json($data);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/match-results', name: 'match_result_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['matchId'], $data['winnerId'], $data['kills'], $data['deaths'], $data['score'], $data['rank'])) {
            return $this->json(['error' => 'Invalid data'], 400);
        }

        $match = $em->getRepository(GameMatch::class)->find($data['matchId']);
        $winner = $em->getRepository(Player::class)->find($data['winnerId']);

        if (!$match || !$winner) {
            return $this->json(['error' => 'Match or player not found'], 404);
        }

        $result = new MatchResult($match, $winner, $data['kills'], $data['deaths'], $data['score'], $data['rank']);

        $em->persist($result);
        $em->flush();

        return $this->json(['message' => 'Match result recorded']);
    }
}

<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\RegistrationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Serializer\SerializerInterface;

class UserController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils, Request $request): JsonResponse
    {
        if ($request->isMethod('GET')) {
            return $this->json(['message' => 'Login page']);
        }

        return $this->json([
            'error' => $authenticationUtils->getLastAuthenticationError() ? 'Invalid credentials' : null,
            'last_username' => $authenticationUtils->getLastUsername(),
        ]);
    }


    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request, RegistrationService $registrationService, SessionInterface $session): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'] ?? null;
        $email = $data['email'] ?? null;
        $plainPassword = $data['password'] ?? null;

        if (empty($username) || empty($email) || empty($plainPassword)) {
            return new JsonResponse([
                'error' => true,
                'message' => 'Username, email, and password are required.'
            ], Response::HTTP_BAD_REQUEST);
        }

        try {
            $registrationService->register($username, $email, $plainPassword);
            $session->set('registered_username', $username);

            return $this->json(['message' => 'User registered']);
        } catch (\Exception $e) {
            return $this->json(['error' => 'Invalid data'], 400);
        }
    }

    #[Route('/profile', name: 'profile', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(SerializerInterface $serializer): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return JsonResponse::class->redirectToRoute('error', ['statusCode' => 400, 'message' => 'Firmware not found.']);
        }

        $userData = $serializer->serialize($user, 'json', ['groups' => ['user:read']]);

        return new JsonResponse($userData, Response::HTTP_OK, [], true);
    }


    #[Route('/logout', name: 'app_logout')]
    public function logout(): JsonResponse
    {
        return new JsonResponse(['message' => 'Logged out']);
    }
}

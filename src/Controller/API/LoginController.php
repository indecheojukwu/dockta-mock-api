<?php

namespace App\Controller\API;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{

    private $manager;
    private $hasher;
    private $logger;

    public  function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, LoggerInterface $logger) {
        $this->manager = $manager;
        $this->logger = $logger;
        $this->hasher = $hasher;
    }

    #[Route(path: '/mock-api/login', name: 'app_mock_login', methods: ['POST'])]
    public function mock_login(Request $request): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $user = $this->manager->getRepository(User::class)->findOneBy(['email' => $data['email']]);

        if (!$user || !$this->hasher->isPasswordValid($user, $data['password'])) {
            return new JsonResponse([
                "success" => false,
                "message" => "Invalid credentials."
            ], 401);
        }

        return new JsonResponse([
            "success" => true,
            "message" => "Successfully logged in.",
            "role" => "Admin",
            "sessionId" => bin2hex(random_bytes(16)),
            "userId" => $user->getId(),
            "name" => $user->getPerson()->getFirstName() . ' ' . $user->getPerson()->getMiddleName() . ' ' . $user->getPerson()->getLastName(),
            "branches" => [[
                "id" => 141,
                "instanceIdentifier" => 241,
                "name" => "Neema Universal HealthCare Hospital",
                "description" => null,
                "nameDescription" => "Neema Universal HealthCare Hospital"
            ]]
        ]);

    }

}

<?php

namespace App\Controller\API;

use App\Entity\Organization;
use App\Entity\Patient;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Routing\Attribute\Route;

class PatientController extends AbstractController
{

    private $manager;
    private $hasher;
    private $logger;
    private $jwtManager;
    private $tokenStorageInterface;

    public  function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, LoggerInterface $logger, TokenStorageInterface $tokenStorageInterface, JWTTokenManagerInterface $jwtManager) {
        $this->manager = $manager;
        $this->logger = $logger;
        $this->hasher = $hasher;
        $this->jwtManager = $jwtManager;
        $this->tokenStorageInterface = $tokenStorageInterface;
    }

    #[Route(path: '/api/get/patient/data', name: 'app_get_patient_details', methods: ['GET'])]
    public function get_patient_details(Request $request): JsonResponse {

        $search_criteria = $request->query->get('search_criteria');

        $patient = $this->manager->getRepository(Patient::class)->fetchPatientRecords($search_criteria);

        return new JsonResponse($patient);
    }
}

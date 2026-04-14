<?php

namespace App\Controller\Auth;

use App\Entity\Admin\Role;
use App\Entity\Admin\UserRole;
use App\Entity\Person;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{

    private $manager;
    private $hasher;
    private $logger;

    public  function __construct(EntityManagerInterface $manager, UserPasswordHasherInterface $hasher, LoggerInterface $logger) {
        $this->manager = $manager;
        $this->logger = $logger;
        $this->hasher = $hasher;
    }

    #[Route(path: '/api/register_user', name: 'app_register', methods: ['POST'])]
    public function register(Request $request): JsonResponse {

        $data = json_decode($request->getContent(), true);

        $em = $this->manager;

        try {

            $em->wrapInTransaction(function($em) use($data){

                $fullName = $data['full_name'];
                $parts = explode(' ', $fullName);

                $firstName = $parts[0] ?? null;
                $middleName = $parts[1] ?? null;
                $lastName = $parts[2] ?? null;

                $person = new Person();
                $person->setFirstName($firstName);
                $person->setMiddleName(count($parts) >= 3 ? $middleName : '');
                $person->setLastName($lastName ?? $middleName ?? '');
                $this->manager->persist($person);
                $this->manager->flush();

                $user = new User();
                $user->setPerson($person);
                $user->setEmail($data['email']);
                $password = $this->hasher->hashPassword($user, $data['password']);
                $user->setPassword($password);
                $this->manager->persist($user);
                $this->manager->flush();

                $role = $this->manager->getRepository(Role::class)->findOneBy([ 'name' => ['ROLE_USER'] ]);

                $user_role = new UserRole();
                $user_role->setUser($user);
                $user_role->setIsPrimary(true);
                $user_role->setIsActive(true);
                $user_role->setRole($role);
                $this->manager->persist($user_role);
                $this->manager->flush();

            });

            return new JsonResponse([
                'success' => true,
            ]);
        } catch (\Exception $e) {
            $this->logger->error('THE ERROR IS ' . $e);
            return new JsonResponse([
                'success' => false,
                'title' => 'Action Failed',
                'message' => 'An Error Occurred. Please Try Again',
            ]);
        }
    }

    #[Route(path: '/api/get/loggedin/user/info', name: 'app_api_logged_in_user_info')]
    public function logged_in_user_info(): JsonResponse {

        // $this->getUser() returns the User object decoded from JWT
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        return new JsonResponse([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'name' => $user->getPerson()->getFirstName() . ' ' . $user->getPerson()->getMiddleName() . ' ' . $user->getPerson()->getLastName(),
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}

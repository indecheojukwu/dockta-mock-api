<?php

namespace App\DataFixtures;

use App\Entity\Admin\Permission;
use App\Entity\Admin\Role;
use App\Entity\Admin\RolePermission;
use App\Entity\Admin\UserRole;
use App\Entity\DoctorService;
use App\Entity\Organization;
use App\Entity\Patient;
use App\Entity\Person;
use App\Entity\Service;
use App\Entity\User;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public $faker;
    public $hasher;
    public $entitymanager;
    public $slug;

    public function __construct(UserPasswordHasherInterface $hasher, EntityManagerInterface $entitymanager, SluggerInterface $slug) {
        $this->faker = Factory::create();
        $this->hasher = $hasher;
        $this->slug = $slug;
        $this->entitymanager = $entitymanager;
    }

    public function load(ObjectManager $manager): void {

        $modules = [
            'PATIENT' => [
                'actions' => ['VIEW', 'DEACTIVATE', 'CREATE', 'UPDATE', 'ACCESS'],
                'roles' => ['ADMIN', 'SUPER ADMIN', 'USER']
            ],
            'ORGANIZATIONS' => [
                'actions' => ['VIEW', 'DEACTIVATE', 'CREATE', 'UPDATE', 'ACCESS'],
                'roles' => ['ADMIN', 'SUPER ADMIN', 'USER']
            ],
        ];

        $actionDescriptions = [
            'VIEW'       => 'VIEW %s RECORDS',
            'CREATE'     => 'CREATE NEW %s RECORDS',
            'UPDATE'     => 'UPDATE EXISTING %s RECORDS',
            'DEACTIVATE' => 'DEACTIVATE %s RECORDS',
        ];

        foreach ($modules as $module => $data) {

            foreach ($data['roles'] as $key => $role) {
                if(!$manager->getRepository(Role::class)->findOneBy(['role_name_alias' => $role])){
                    $system_roles = new Role();
                    $system_roles->setName('ROLE_' . $this->slug->slug(strtoupper($role), '_'));
                    $system_roles->setRoleNameAlias($role);
                    $system_roles->setIsActive(true);
                    $system_roles->setIsDeleted(false);
                    $manager->persist($system_roles);
                    $manager->flush();
                }
            }

            foreach ($data['actions'] as $key => $perm) {

                $description = sprintf( $actionDescriptions[$perm] ?? '%s %s', $module, $perm);

                $permission = new Permission();
                $permission->setName($perm);
                $permission->setModule($module);
                $permission->setDescription($description);
                $normalizedModule = str_replace(' ', '_', strtoupper($module));
                $permission->setAction($this->slug->slug(strtoupper($perm) . '_' . $normalizedModule, '_'));
                $permission->setIsActive(true);
                $permission->setIsDeleted(false);
                $manager->persist($permission);
            }
        }

        $manager->flush();

        // RolePermission
        foreach ($modules as $module => $data) {
            foreach ($data['actions'] as $permission) {

                foreach ($data['roles'] as $key => $role) {
                    $perm = $manager->getRepository(Permission::class)->findOneBy(['name' => strtoupper($permission), 'module' => strtoupper($module)]);
                    $role_entity = $manager->getRepository(Role::class)->findOneBy(['role_name_alias' => strtoupper($role)]);
                    $role_permission_exists = $manager->getRepository(RolePermission::class)->findOneBy(['role' => $role_entity, 'permission' => $perm]);

                    if(!$role_permission_exists){

                        $set_all_permissions_active = rand(1, 0);

                        $rolepermission = new RolePermission();
                        $rolepermission->setPermission($perm);
                        if(strtoupper($role) == 'SUPER ADMIN' || strtoupper($role) == 'ADMIN'){
                            $set_all_permissions_active = 1;
                        }
                        $rolepermission->setIsActive($set_all_permissions_active);
                        $rolepermission->setRole($role_entity);
                        $manager->persist($rolepermission);
                    }
                }
            }
        }

        for ($i = 0; $i < 6; $i++) {
            $person = new Person();
            $person->setAge($this->faker->numberBetween(18, 60));
            $person->setGender($this->faker->randomElement(['male', 'female']));
            $person->setFirstName($this->faker->firstName);
            $person->setLastName($this->faker->lastName);
            $person->setNationalId($this->faker->unique()->numberBetween(10000000, 99999999));
            $person->setNssfNumber($this->faker->unique()->numberBetween(10000000, 99999999));
            $person->setPhonenumber($this->faker->phoneNumber);
            $person->setUsername($this->faker->unique()->userName);

            $manager->persist($person);

            $work_user = new User();
            $work_user->setEmail($this->faker->unique()->safeEmail);
            $password = $this->hasher->hashPassword($work_user, 'zeus');
            $work_user->setPassword($password);
            $work_user->setPerson($person);
            $work_user->setPhonenumber($this->faker->phoneNumber);
            $manager->persist($work_user);
        }

        $person = new Person();
        $person->setAge(23);
        $person->setGender('Male');
        $person->setFirstName('Evans');
        $person->setLastName('Indeche');
        $person->setNationalId($this->faker->numberBetween(100000,1000000));
        $person->setNssfNumber($this->faker->numberBetween(100000,1000000));
        $person->setPhonenumber('0720389023');
        $person->setUsername('obrien');
        $manager->persist($person);

        $work_user_1 = new User();
        $work_user_1->setEmail('work.ojukwu.e@gmail.com');
        $password = $this->hasher->hashPassword($work_user_1, 'zeus');
        $work_user_1->setPassword($password);
        $work_user_1->setPerson($person);
        $work_user_1->setPhonenumber('0720389023');
        $manager->persist($work_user_1);

        $person2 = new Person();
        $person2->setAge(23);
        $person2->setGender('Male');
        $person2->setFirstName('Winston');
        $person2->setLastName('Wacieni');
        $person2->setNationalId($this->faker->numberBetween(100000,1000000));
        $person2->setNssfNumber($this->faker->numberBetween(100000,1000000));
        $person2->setPhonenumber('0720389023');
        $person2->setNssfNumber('1203939');
        $person2->setUsername('winston');
        $manager->persist($person2);

        $work_user_2 = new User();
        $work_user_2->setEmail('work.winston@gmail.com');
        $password = $this->hasher->hashPassword($work_user_2, 'poseidon');
        $work_user_2->setPhonenumber('0720389023');
        $work_user_2->setPassword($password);
        $work_user_2->setPerson($person2);
        $manager->persist($work_user_2);

        $person = new Person();
        $person->setAge(23);
        $person->setGender('Male');
        $person->setFirstName('SAM');
        $person->setLastName('SAM');
        $person->setNationalId($this->faker->numberBetween(100000,1000000));
        $person->setNssfNumber($this->faker->numberBetween(100000,1000000));
        $person->setPhonenumber('0720389023');
        $person->setUsername('SAM');
        $manager->persist($person);

        $work_user_3 = new User();
        $work_user_3->setEmail('work.sam@gmail.com');
        $work_user_3->setPhonenumber('0720389023');
        $password = $this->hasher->hashPassword($work_user_3, 'prometheus');
        $work_user_3->setPassword($password);
        $work_user_3->setPerson($person);
        $manager->persist($work_user_3);

        $person = new Person();
        $person->setAge(23);
        $person->setGender('Male');
        $person->setFirstName('Evans');
        $person->setLastName('Indeche');
        $person->setNationalId($this->faker->numberBetween(100000,1000000));
        $person->setNssfNumber($this->faker->numberBetween(100000,1000000));
        $person->setPhonenumber('0720389023');
        $person->setUsername('obrien');
        $manager->persist($person);

        $admin_user_1 = new User();
        $admin_user_1->setEmail('i.ojukwu.e@gmail.com');
        $password = $this->hasher->hashPassword($admin_user_1, 'zeus');
        $admin_user_1->setPassword($password);
        $admin_user_1->setPerson($person);
        $admin_user_1->setPhonenumber('0720389023');
        $manager->persist($admin_user_1);

        // 3. Create Second Admin (Winston)
        $person2 = new Person();
        $person2->setAge(23);
        $person2->setGender('Male');
        $person2->setFirstName('Winston');
        $person2->setLastName('Wacieni');
        $person2->setNationalId($this->faker->numberBetween(100000,1000000));
        $person2->setNssfNumber($this->faker->numberBetween(100000,1000000));
        $person2->setPhonenumber('0720389023');
        $person2->setNssfNumber('1203939');
        $person2->setUsername('winston');
        $manager->persist($person2);

        $admin_user_2 = new User();
        $admin_user_2->setEmail('cloud.winston@gmail.com');
        $password = $this->hasher->hashPassword($admin_user_2, 'poseidon');
        $admin_user_2->setPhonenumber('0720389023');
        $admin_user_2->setPassword($password);
        $admin_user_2->setPerson($person2);
        $manager->persist($admin_user_2);

        $person = new Person();
        $person->setAge(23);
        $person->setGender('Male');
        $person->setFirstName('SAM');
        $person->setLastName('SAM');
        $person->setNationalId($this->faker->numberBetween(100000,1000000));
        $person->setNssfNumber($this->faker->numberBetween(100000,1000000));
        $person->setPhonenumber('0720389023');
        $person->setUsername('SAM');
        $manager->persist($person);

        $admin_user_3 = new User();
        $admin_user_3->setEmail('sam@gmail.com');
        $admin_user_3->setPhonenumber('0720389023');
        $password = $this->hasher->hashPassword($admin_user_3, 'prometheus');
        $admin_user_3->setPassword($password);
        $admin_user_3->setPerson($person);
        $manager->persist($admin_user_3);

        $admin_super_admins_roles = $manager->getRepository(Role::class)->findBy([
            'name' => ['ROLE_SUPER_ADMIN']
        ]);

        $admins = [$admin_user_1, $admin_user_2, $admin_user_3];
        foreach($admins as $admin){
            foreach($admin_super_admins_roles as $asa){
                $user_role = new UserRole();
                $user_role->setUser($admin);
                $user_role->setIsPrimary(true);
                $user_role->setIsActive(true);
                $user_role->setRole($asa);
                $manager->persist($user_role);
            }
        }

        $manager->flush();

        $services = [
            ['name' => 'General Consultation', 'code' => 'CONSULT', 'description' => 'General medical consultation', 'price' => 5000],
            ['name' => 'Cardiology Checkup', 'code' => 'CARDIO', 'description' => 'Heart and cardiovascular system checkup', 'price' => 15000],
            ['name' => 'Dental Checkup', 'code' => 'DENTAL', 'description' => 'Dental examination and cleaning', 'price' => 8000],
            ['name' => 'Eye Examination', 'code' => 'EYE', 'description' => 'Vision and eye health examination', 'price' => 7000],
            ['name' => 'Orthopedic Consultation', 'code' => 'ORTHO', 'description' => 'Bone and joint consultation', 'price' => 12000],
            ['name' => 'Pediatric Checkup', 'code' => 'PEDIA', 'description' => 'Child health checkup', 'price' => 6000],
            ['name' => 'Laboratory Test', 'code' => 'LAB', 'description' => 'Various laboratory tests', 'price' => 3000],
            ['name' => 'X-Ray Scan', 'code' => 'XRAY', 'description' => 'X-ray imaging services', 'price' => 10000],
            ['name' => 'Ultrasound Scan', 'code' => 'ULTRA', 'description' => 'Ultrasound diagnostic services', 'price' => 9000],
            ['name' => 'Physical Therapy', 'code' => 'PT', 'description' => 'Physiotherapy sessions', 'price' => 8000],
            ['name' => 'Vaccination', 'code' => 'VAX', 'description' => 'Vaccination services', 'price' => 2000],
            ['name' => 'Mental Health Consultation', 'code' => 'MENTAL', 'description' => 'Psychiatric and counseling services', 'price' => 11000],
        ];

        $serviceEntities = [];
        foreach ($services as $data) {
            $service = new Service();
            $service->setName($data['name']);
            $service->setCode($data['code']);
            $service->setDescription($data['description']);
            $service->setPrice($data['price']);
            $manager->persist($service);
            $serviceEntities[] = $service;
        }

        $manager->flush();

        $patients = [];
        $bloodGroups = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
        for ($i = 0; $i < 15; $i++) {
            $patient = new Patient();
            $patient->setFullName($this->faker->name);
            $patient->setDateAdmitted($this->faker->dateTimeBetween('-6 months', 'now'));
            $patient->setPatientNumber('#PTNT-00' . $i);
            $patient->setIsMale($this->faker->boolean);
            $patient->setBloodgroup($this->faker->randomElement($bloodGroups));
            $patient->setDateOfBirth($this->faker->dateTimeBetween('-50 years', '-18 years'));
            $patient->setAddress($this->faker->address);
            $patient->setEmail($this->faker->safeEmail);
            $patient->setPhonenumber($this->faker->phoneNumber);
            $manager->persist($patient);
            $patients[] = $patient;
        }

        $manager->flush();

        $excludedIds = ['i.ojukwu.e@gmail.com', 'cloud.winston@gmail.com', 'sam@gmail.com'];
        $doctors = $manager->getRepository(User::class)->createQueryBuilder('u')->where('u.email IN (:excludedIds)')->setParameter('excludedIds', $excludedIds)->getQuery()->getResult();
        $count = 0;
        for ($i = 0; $i < 20; $i++) {
            if (empty($doctors)) {
                break;
            }

            $doctorService = new DoctorService();
            $doctorService->setService($this->faker->randomElement($serviceEntities));
            $doctorService->setDoctor($this->faker->randomElement($doctors));
            $doctorService->setPatient($this->faker->randomElement($patients));
            $doctorService->setNotes([ $this->faker->sentence ]);
            $manager->persist($doctorService);
            $count++;
        }

        $manager->flush();
    }
}

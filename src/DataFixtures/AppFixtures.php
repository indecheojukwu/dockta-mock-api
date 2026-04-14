<?php

namespace App\DataFixtures;

use App\Entity\Admin\Permission;
use App\Entity\Admin\Role;
use App\Entity\Admin\RolePermission;
use App\Entity\Admin\UserRole;
use App\Entity\Organization;
use App\Entity\Person;
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

        // 2. Create First Admin (Evans)
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
        $admin_user_1->setEmail('work.ojukwu.e@gmail.com');
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
        $admin_user_2->setEmail('work.winston@gmail.com');
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
        $admin_user_3->setEmail('work.sam@gmail.com');
        $admin_user_3->setPhonenumber('0720389023');
        $password = $this->hasher->hashPassword($admin_user_3, 'prometheus');
        $admin_user_3->setPassword($password);
        $admin_user_3->setPerson($person);
        $manager->persist($admin_user_3);

        $manager->flush();

        // FIX: Use the objects we just created directly. No need to query DB.
        $admins = [$admin_user_1, $admin_user_2, $admin_user_3];
        $admin_super_admins_roles = $manager->getRepository(Role::class)->findBy([
            'name' => ['ROLE_SUPER_ADMIN']
        ]);

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
    }
}

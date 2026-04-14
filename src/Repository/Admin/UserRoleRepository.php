<?php

namespace App\Repository\Admin;

use App\Entity\Admin\UserRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserRole>
 */
class UserRoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, UserRole::class);
    }

    public function fetchMostRecentCreatedUserRoles(): array {
        return $this->createQueryBuilder('ur')
            ->select([
                "CONCAT(p.first_name, ' ', p.last_name) as user_name",
                'r.role_name_alias as role_name', 'r.icon_thumb as role_icon', 'r.color as role_color', 'r.id as role_id',
                'u.id as user_id'
            ])
            ->join('ur.user', 'u')
            ->join('u.person', 'p')
            ->join('ur.role', 'r')
            ->andWhere('ur.is_active = :is_active')
            ->andWhere('r.name != :super_admin_role')
            ->setParameter('is_active', 1)
            ->setParameter('super_admin_role', 'ROLE_SUPER_ADMIN')
            ->orderBy('ur.createdAt', 'DESC')
            ->setMaxResults('10')
            ->getQuery()
            ->getResult();
    }

    // this only gets active user_roles
    // so later we need a different UI to see inactive user roles and users with roles
    public function findUserInRoleByFirstNameFirstCharacter($first_name_letter): array {
        return $this->createQueryBuilder('ur')
            ->select([
                "CONCAT(p.first_name, ' ', p.last_name) as user_name",
                'r.role_name_alias as role_name', 'r.icon_thumb as role_icon', 'r.color as role_color', 'r.id as role_id',
                'u.id as user_id'
            ])
            ->join('ur.user', 'u')
            ->join('u.person', 'p')
            ->join('ur.role', 'r')
            ->andWhere('ur.is_active = :is_active')
            ->andWhere('p.first_name LIKE :first_name')
            ->setParameter('first_name', $first_name_letter . '%')
            ->setParameter('is_active', 1)
            ->orderBy('ur.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function searchUsers(string $term): array {
        $qb = $this->createQueryBuilder('ur')
            ->select([
                "CONCAT(p.first_name, ' ', p.last_name) as user_name",
                'r.role_name_alias as role_name', 'r.icon_thumb as role_icon', 'r.color as role_color', 'r.id as role_id',
                'u.id as user_id'
            ])
            ->join('ur.user', 'u')
            ->join('u.person', 'p')
            ->join('ur.role', 'r')
            ->andWhere('ur.is_active = :is_active')
            ->setParameter('is_active', 1);

        // If term looks like a number, also search by user ID
        if (is_numeric($term)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->eq('u.id', ':userId'),
                    $qb->expr()->like("CONCAT(p.first_name, ' ', p.last_name)", ':term'),
                    $qb->expr()->like('r.role_name_alias', ':term')
                )
            )
            ->setParameter('userId', (int) $term)
            ->setParameter('term', '%' . $term . '%');
        } else {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like("CONCAT(p.first_name, ' ', p.last_name)", ':term'),
                    $qb->expr()->like('r.role_name_alias', ':term')
                )
            )
            ->setParameter('term', '%' . $term . '%');
        }

        return $qb->orderBy('p.first_name', 'ASC')
            ->setMaxResults(25)
            ->getQuery()
            ->getResult();
    }

}

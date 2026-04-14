<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Role>
 */
class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Role::class);
    }

    public function getAllRoles($organization): array {
        $roles = $this->createQueryBuilder('r')
            ->select([
                'r.id as role_id', 'r.role_name_alias as role_name', 'r.icon', 'r.color', 'r.description', 'r.icon_thumb',
                'r.is_active', 'r.is_deleted',
                'COALESCE(COUNT(rp.id),0) as permission_count'
            ])
            ->leftJoin(\App\Entity\Admin\RolePermission::class, 'rp', 'WITH', 'rp.role = r AND rp.is_active = 1')
            ->groupBy('r.id, r.name, r.role_name_alias, r.icon, r.color, r.description, r.is_active, r.is_deleted')
            ->andWhere('r.organization = :organization')
            ->setParameter('organization', $organization)
            ->orderBy('r.is_deleted', 'ASC')
            ->addOrderBy('r.role_name_alias', 'ASC')
            ->getQuery()
            ->getResult();

        $structured_roles = [];
        foreach ($roles as $role) {
            $firstChar = strtoupper($role['role_name'][0]);
            $isDeactivated = $role['is_deleted'] || !$role['is_active'];
            $structured_roles[$firstChar][] = [
                'role_id' => $role['role_id'],
                'role_name' => $role['role_name'],
                'icon' => $role['icon'],
                'icon_thumb' => $role['icon_thumb'],
                'color' => $role['color'],
                'permission_count' => $role['permission_count'],
                'is_deactivated' => $isDeactivated,
            ];
        }

        return $structured_roles;
    }

    public function getAllRoleUsers($offset, $rows, $additionalFilter, $organization, $role_id): array {
        $roles = $this->createQueryBuilder('r')
            ->select([
                'r.id as role_id',
                'u.id as user_id',
                "CONCAT(p.first_name, ' ', p.last_name) as user_name",
            ])
            ->join('r.userRoles', 'ur')
            ->join('ur.user', 'u')
            ->join('u.person', 'p')
            ->andWhere('r.organization = :organization')
            ->andWhere('r.id = :role_id')
            ->setParameter('organization', $organization)
            ->setParameter('role_id', $role_id)
            ->getQuery()
            ->getResult();

        return $roles;
    }

}

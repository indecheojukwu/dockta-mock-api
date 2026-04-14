<?php

namespace App\Repository\Admin;

use App\Entity\Admin\Permission;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PermissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Permission::class);
    }

    public function fetchAllRolePermissions($role_id): array {

        $permissions = $this->createQueryBuilder('p')
            ->select([
                'p.name as perm_name', 'p.module as perm_module', 'p.action as module_perm_action', 'p.id as perm_id',
                'CASE WHEN rp.id IS NOT NULL THEN 1 ELSE 0 END as role_default_permission',
            ])
            ->leftJoin(\App\Entity\Admin\RolePermission::class, 'rp', 'WITH', 'rp.permission = p AND rp.role = :role AND rp.is_active = 1')
            ->setParameter('role', $role_id)
            ->getQuery()
            ->getArrayResult();

        $structured_perms = [];
        foreach ($permissions as $permission) {
            $structured_perms[$permission['perm_module']][] = [
                'perm_name' => $permission['perm_name'],
                'perm_id' => $permission['perm_id'],
                'module_perm_action' => str_replace('_', ' ', $permission['module_perm_action']),
                'role_default_permission' => $permission['role_default_permission'],
            ];
        }

        return $structured_perms;
    }

    public function fetchAllActiveUserPermissionOverrides($user_id): array {
        $user_override_permissions = $this->createQueryBuilder('p')
            ->select([
                'p.name as perm_name', 'p.module as perm_module', 'p.action as module_perm_action', 'p.id as perm_id',
                'CASE
                WHEN upo.id IS NOT NULL AND upo.is_denied = 0 THEN 1
                WHEN upo.id IS NOT NULL AND upo.is_denied = 1 THEN 0
                ELSE -1
                END AS user_granted_permission'
            ])
            ->leftJoin(\App\Entity\Admin\UserPermissionOverride::class, 'upo', 'WITH', 'upo.permission = p AND upo.user = :user_id AND upo.is_active = 1')
            ->setParameter('user_id', $user_id)
            ->getQuery()
            ->getArrayResult();

        $structured_upo = [];
        foreach ($user_override_permissions as $upo) {
            $structured_upo[$upo['perm_module']][] = [
                'perm_name' => $upo['perm_name'],
                'perm_id' => $upo['perm_id'],
                'module_perm_action' => str_replace('_', ' ', $upo['module_perm_action']),
                'user_granted_permission' => $upo['user_granted_permission'],
            ];
        }

        return $structured_upo;
    }


    public function fetchAllModulePermissions(): array {

        $permissions = $this->createQueryBuilder('p')
            ->select([
                'p.name as perm_name', 'p.module as perm_module', 'p.action as module_perm_action', 'p.id as perm_id',
            ])
            ->getQuery()
            ->getArrayResult();

        $structured_perms = [];
        foreach ($permissions as $permission) {
            $structured_perms[$permission['perm_module']][] = [
                'perm_name' => $permission['perm_name'],
                'perm_id' => $permission['perm_id'],
                'module_perm_action' => str_replace('_', ' ', $permission['module_perm_action']),
            ];
        }

        return $structured_perms;
    }

}

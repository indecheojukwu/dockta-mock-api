<?php

namespace App\Repository\Admin;

use App\Entity\Admin\UserPermissionOverride;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserPermissionOverride>
 */
class UserPermissionOverrideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, UserPermissionOverride::class);
    }

    public function fetchUserPermissions($user_id): array {
        return $this->createQueryBuilder('upo')
            ->select([
                'upo.is_denied', 'IDENTITY(upo.permission) as permission_id',
            ])
            ->where('upo.user = :user_id')
            ->andWhere('upo.is_active = :is_active')
            ->setParameter('user_id', $user_id)
            ->setParameter('is_active', 1)
            ->getQuery()
            ->getResult();

    }

}

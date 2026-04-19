<?php

namespace App\Repository;

use App\Entity\Patient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Patient>
 */
class PatientRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Patient::class);
    }

    public function fetchPatientRecords($patient_slug): array {
        return $this->createQueryBuilder('p')
            ->select([
                'p.id', 'p.full_name',
                "DATE_FORMAT(p.date_admitted, '%Y-%m-%d') AS date_admitted",
                "CASE WHEN p.is_male = 1 THEN 'male' ELSE 'female' END as gender",
                'p.blood_group',
                "DATE_FORMAT(p.date_of_birth, '%Y-%m-%d') AS date_of_birth",
                'p.address', 'p.email',
                'p.phonenumber', 'p.patient_number'
            ])
            ->where('p.patient_number = :slug')
            ->orWhere('p.full_name = :slug')
            ->setParameter('slug', $patient_slug)
            ->groupBy('p.id', 'p.full_name', 'p.date_admitted', 'p.is_male', 'p.blood_group', 'p.date_of_birth', 'p.address', 'p.email', 'p.phonenumber', 'p.patient_number',)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function selectAll(): array {
        return $this->createQueryBuilder('p')
            ->select([
                'p.id', 'p.full_name', 'p.date_admitted', 'p.is_male', 'p.bloodgroup', 'p.date_of_birth', 'p.address', 'p.email', 'p.phonenumber', 'p.patient_number',
            ])
            ->orderBy('p.id', 'ASC')
            ->groupBy('p.id', 'p.full_name', 'p.date_admitted', 'p.is_male', 'p.blood_group', 'p.date_of_birth', 'p.address', 'p.email', 'p.phonenumber', 'p.patient_number',)
            ->getQuery()
            ->getResult();
    }
}

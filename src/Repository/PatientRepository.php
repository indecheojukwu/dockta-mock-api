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

    public function fetchPatientRecords($patient_slug, $org_user_id): array {
        $rows =  $this->createQueryBuilder('p')
            ->select([
                'p.id', 'p.full_name', 'p.date_admitted', 'p.is_male', 'p.blood_group', 'p.date_of_birth', 'p.address', 'p.email', 'p.phonenumber', 'p.patient_number',
                'ps.id AS patient_service_id', 'ps.total_amount as service_total_amount', 'ps.has_insurance',
                's.name AS service_name', 's.code AS service_code',
                'ii.id AS invoice_item_id', 'ii.unit_price', 'ii.total AS invoice_item_total',
                'i.id AS invoice_id', 'i.invoice_number', 'i.status AS invoice_status', 'i.total_amount AS invoice_total',
                'pay.id AS payment_id', 'pay.amount AS payment_amount', 'pay.paid_by', 'pay.payment_method'
            ])
            ->leftJoin(\App\Entity\PatientService::class, 'ps', 'WITH', 'ps.patient = p AND ps.user = :org_user_id')
            ->leftJoin(\App\Entity\Service::class, 's', 'WITH', 's = ps.service')
            ->leftJoin(\App\Entity\InvoiceItem::class, 'ii', 'WITH', 'ii.patient_service = ps')
            ->leftJoin(\App\Entity\Invoice::class, 'i', 'WITH', 'i = ii.invoice')
            ->leftJoin(\App\Entity\Payment::class, 'pay', 'WITH', 'pay.invoice = i')
            ->where('p.patient_number = :slug')
            ->setParameter('slug', $patient_slug)
            ->setParameter('org_user_id', $org_user_id)
            ->getQuery()
            ->getArrayResult();

        if (!$rows) return [];

        $patient = [
            'id' => $rows[0]['id'],
            'full_name' => $rows[0]['full_name'],
            'gender' => $rows[0]['is_male'] ? 'male' : 'female',
            'patient_number' => $rows[0]['patient_number'],
            'services' => []
        ];

        foreach ($rows as $row) {

            $serviceId = $row['patient_service_id'];

            if (!isset($patient['services'][$serviceId])) {
                $patient['services'][$serviceId] = [
                    'id' => $serviceId,
                    'service_name' => $row['service_name'],
                    'amount' => $row['service_total_amount'],
                    'has_insurance' => $row['has_insurance'],
                    'invoice' => null,
                    'payments' => []
                ];
            }

            // Attach invoice (once)
            if ($row['invoice_id']) {
                $patient['services'][$serviceId]['invoice'] = [
                    'id' => $row['invoice_id'],
                    'invoice_number' => $row['invoice_number'],
                    'status' => $row['invoice_status'],
                    'total' => $row['invoice_total']
                ];
            }

            // Attach payments (many)
            if ($row['payment_id']) {
                $patient['services'][$serviceId]['payments'][] = [
                    'id' => $row['payment_id'],
                    'amount' => $row['payment_amount'],
                    'paid_by' => $row['paid_by'],
                    'method' => $row['payment_method']
                ];
            }
        }

        $patient['services'] = array_values($patient['services']);

        return $patient;
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

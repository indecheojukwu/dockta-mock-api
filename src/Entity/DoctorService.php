<?php

namespace App\Entity;

use App\Repository\DoctorServiceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: DoctorServiceRepository::class)]
class DoctorService
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'doctorServices')]
    private ?Service $service = null;

    #[ORM\ManyToOne(inversedBy: 'doctorServices')]
    private ?User $doctor = null;

    #[ORM\ManyToOne(inversedBy: 'doctorServices')]
    private ?Patient $patient = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private mixed $notes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;

        return $this;
    }

    public function getDoctor(): ?User
    {
        return $this->doctor;
    }

    public function setDoctor(?User $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getNotes(): mixed
    {
        return $this->notes;
    }

    public function setNotes(mixed $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}

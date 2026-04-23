<?php

namespace App\Entity;

use App\Repository\PatientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: PatientRepository::class)]
class Patient
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 65)]
    private ?string $full_name = null;

    #[ORM\Column]
    private ?\DateTime $date_admitted = null;

    #[ORM\Column]
    private ?bool $is_male = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $blood_group = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $date_of_birth = null;

    /**
     * @var Collection<int, DoctorService>
     */
    #[ORM\OneToMany(targetEntity: DoctorService::class, mappedBy: 'patient')]
    private Collection $doctorServices;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $phonenumber = null;

    #[ORM\Column(length: 20)]
    private ?string $patient_number = null;

    /**
     * @var Collection<int, PatientService>
     */
    #[ORM\OneToMany(targetEntity: PatientService::class, mappedBy: 'patientt')]
    private Collection $patientServices;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'patient')]
    private Collection $invoices;

    public function __construct()
    {
        $this->doctorServices = new ArrayCollection();
        $this->patientServices = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName(): ?string
    {
        return $this->full_name;
    }

    public function setFullName(string $full_name): static
    {
        $this->full_name = $full_name;

        return $this;
    }

    public function getDateAdmitted(): ?\DateTime
    {
        return $this->date_admitted;
    }

    public function setDateAdmitted(\DateTime $date_admitted): static
    {
        $this->date_admitted = $date_admitted;

        return $this;
    }

    public function isMale(): ?bool
    {
        return $this->is_male;
    }

    public function setIsMale(bool $is_male): static
    {
        $this->is_male = $is_male;

        return $this;
    }

    public function getBloodgroup(): ?string
    {
        return $this->blood_group;
    }

    public function setBloodgroup(?string $blood_group): static
    {
        $this->blood_group = $blood_group;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTime
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTime $date_of_birth): static
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    /**
     * @return Collection<int, DoctorService>
     */
    public function getDoctorServices(): Collection
    {
        return $this->doctorServices;
    }

    public function addDoctorService(DoctorService $doctorService): static
    {
        if (!$this->doctorServices->contains($doctorService)) {
            $this->doctorServices->add($doctorService);
            $doctorService->setPatient($this);
        }

        return $this;
    }

    public function removeDoctorService(DoctorService $doctorService): static
    {
        if ($this->doctorServices->removeElement($doctorService)) {
            // set the owning side to null (unless already changed)
            if ($doctorService->getPatient() === $this) {
                $doctorService->setPatient(null);
            }
        }

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): static
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getPatientNumber(): ?string
    {
        return $this->patient_number;
    }

    public function setPatientNumber(string $patient_number): static
    {
        $this->patient_number = $patient_number;

        return $this;
    }

    /**
     * @return Collection<int, PatientService>
     */
    public function getPatientServices(): Collection
    {
        return $this->patientServices;
    }

    public function addPatientService(PatientService $patientService): static
    {
        if (!$this->patientServices->contains($patientService)) {
            $this->patientServices->add($patientService);
            $patientService->setPatientt($this);
        }

        return $this;
    }

    public function removePatientService(PatientService $patientService): static
    {
        if ($this->patientServices->removeElement($patientService)) {
            // set the owning side to null (unless already changed)
            if ($patientService->getPatientt() === $this) {
                $patientService->setPatientt(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getInvoices(): Collection
    {
        return $this->invoices;
    }

    public function addInvoice(Invoice $invoice): static
    {
        if (!$this->invoices->contains($invoice)) {
            $this->invoices->add($invoice);
            $invoice->setPatient($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getPatient() === $this) {
                $invoice->setPatient(null);
            }
        }

        return $this;
    }

}

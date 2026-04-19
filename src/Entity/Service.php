<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column()]
    private ?int $price = null;

    /**
     * @var Collection<int, DoctorService>
     */
    #[ORM\OneToMany(targetEntity: DoctorService::class, mappedBy: 'service')]
    private Collection $doctorServices;

    public function __construct()
    {
        $this->doctorServices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

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
            $doctorService->setService($this);
        }

        return $this;
    }

    public function removeDoctorService(DoctorService $doctorService): static
    {
        if ($this->doctorServices->removeElement($doctorService)) {
            // set the owning side to null (unless already changed)
            if ($doctorService->getService() === $this) {
                $doctorService->setService(null);
            }
        }

        return $this;
    }
}

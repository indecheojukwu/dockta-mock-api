<?php

namespace App\Entity\Admin;

use App\Entity\User;
use App\Repository\Admin\UserPermissionOverrideRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Index(name: 'idx_user_id', columns: ['user_id', 'permission_id'])]
#[ORM\Entity(repositoryClass: UserPermissionOverrideRepository::class)]
class UserPermissionOverride
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userPermissionOverrides')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userPermissionOverrides')]
    private ?Permission $permission = null;

    #[ORM\Column]
    private ?bool $is_denied = null;

    #[ORM\ManyToOne(inversedBy: 'userPermissionOverrides')]
    #[ORM\JoinColumn(name:'granted_by', nullable: true)]
    private ?User $granted_by = null;

    #[ORM\ManyToOne(inversedBy: 'userPermissionOverrides')]
    #[ORM\JoinColumn(name:'denied_by', nullable: true)]
    private ?User $denied_by = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $is_active = false;

    public function getId(): ?int {
        return $this->id;
    }

    public function getUser(): ?User {
        return $this->user;
    }

    public function setUser(?User $user): static {
        $this->user = $user;

        return $this;
    }

    public function getPermission(): ?Permission {
        return $this->permission;
    }

    public function setPermission(?Permission $permission): static {
        $this->permission = $permission;

        return $this;
    }

    public function isDenied(): ?bool {
        return $this->is_denied;
    }

    public function setIsDenied(bool $is_denied): static {
        $this->is_denied = $is_denied;

        return $this;
    }

    public function getGrantedBy(): ?User {
        return $this->granted_by;
    }

    public function setGrantedBy(?User $granted_by): static {
        $this->granted_by = $granted_by;

        return $this;
    }

    public function getDeniedBy(): ?User {
        return $this->denied_by;
    }

    public function setDeniedBy(?User $denied_by): static {
        $this->denied_by = $denied_by;

        return $this;
    }

    public function isActive(): ?bool {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static {
        $this->is_active = $is_active;

        return $this;
    }
}

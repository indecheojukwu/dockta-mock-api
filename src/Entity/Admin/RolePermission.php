<?php

namespace App\Entity\Admin;

use App\Entity\User;
use App\Repository\Admin\RolePermissionRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: RolePermissionRepository::class)]
class RolePermission
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'rolePermissions')]
    private ?Role $role = null;

    #[ORM\ManyToOne(inversedBy: 'rolePermissions')]
    private ?Permission $permission = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $is_active = false;

    #[ORM\ManyToOne(inversedBy: 'rolePermissions')]
    #[ORM\JoinColumn(name: 'granted_by', nullable: true)]
    private ?User $granted_by = null;

    #[ORM\ManyToOne(inversedBy: 'rolePermissions')]
    #[ORM\JoinColumn(name: 'removed_by', nullable: true)]
    private ?User $removed_by = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function getRole(): ?Role {
        return $this->role;
    }

    public function setRole(?Role $role): static {
        $this->role = $role;

        return $this;
    }

    public function getPermission(): ?Permission {
        return $this->permission;
    }

    public function setPermission(?Permission $permission): static {
        $this->permission = $permission;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static
    {
        $this->is_active = $is_active;

        return $this;
    }

    public function getGrantedBy(): ?User
    {
        return $this->granted_by;
    }

    public function setGrantedBy(?User $granted_by): static
    {
        $this->granted_by = $granted_by;

        return $this;
    }

    public function getRemovedBy(): ?User
    {
        return $this->removed_by;
    }

    public function setRemovedBy(?User $removed_by): static
    {
        $this->removed_by = $removed_by;

        return $this;
    }
}

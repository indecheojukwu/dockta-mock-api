<?php

namespace App\Entity\Admin;

use App\Entity\User;
use App\Repository\Admin\UserRoleRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Table(name: "user_role", indexes: [ new ORM\Index(name: "idx_user_role_pair", columns: ["user_id", "role_id"]) ])]
#[ORM\Entity(repositoryClass: UserRoleRepository::class)]
class UserRole
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userRoles')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userRoles')]
    private ?Role $role = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $is_active = false;

    #[ORM\ManyToOne(inversedBy: 'userGrantedRoles')]
    #[ORM\JoinColumn(name: 'granted_by', nullable: true)]
    private ?User $granted_by = null;

    #[ORM\ManyToOne(inversedBy: 'userRevokedUserRoles')]
    #[ORM\JoinColumn(name: 'revoked_by', nullable: true)]
    private ?User $revoked_by = null;

    #[ORM\Column(options: ['defaukt' => false])]
    private ?bool $is_primary = false;

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

    public function getRole(): ?Role {
        return $this->role;
    }

    public function setRole(?Role $role): static {
        $this->role = $role;

        return $this;
    }

    public function isActive(): ?bool {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static {
        $this->is_active = $is_active;

        return $this;
    }

    public function getGrantedBy(): ?User {
        return $this->granted_by;
    }

    public function setGrantedBy(?User $granted_by): static {
        $this->granted_by = $granted_by;

        return $this;
    }

    public function getRevokedBy(): ?User {
        return $this->revoked_by;
    }

    public function setRevokedBy(?User $revoked_by): static {
        $this->revoked_by = $revoked_by;

        return $this;
    }

    public function isPrimary(): ?bool
    {
        return $this->is_primary;
    }

    public function setIsPrimary(bool $is_primary): static
    {
        $this->is_primary = $is_primary;

        return $this;
    }
}

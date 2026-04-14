<?php

namespace App\Entity\Admin;

use App\Repository\Admin\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: RoleRepository::class)]
class Role
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $color = null;

    /**
     * @var Collection<int, UserRole>
     */
    #[ORM\OneToMany(targetEntity: UserRole::class, mappedBy: 'role')]
    private Collection $userRoles;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $icon = null;

    #[ORM\Column(length: 25)]
    private ?string $role_name_alias = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column]
    private ?bool $is_deleted = null;

    /**
     * @var Collection<int, RolePermission>
     */
    #[ORM\OneToMany(targetEntity: RolePermission::class, mappedBy: 'role')]
    private Collection $rolePermissions;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $icon_thumb = null;

    public function __construct() {
        $this->userRoles = new ArrayCollection();
        $this->rolePermissions = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getName(): ?string {
        return $this->name;
    }

    public function setName(string $name): static {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string {
        return $this->description;
    }

    public function setDescription(?string $description): static {
        $this->description = $description;

        return $this;
    }

    public function getColor(): ?string {
        return $this->color;
    }

    public function setColor(?string $color): static {
        $this->color = $color;

        return $this;
    }

    /**
     * @return Collection<int, UserRole>
     */
    public function getUserRoles(): Collection {
        return $this->userRoles;
    }

    public function addUserRole(UserRole $userRole): static {
        if (!$this->userRoles->contains($userRole)) {
            $this->userRoles->add($userRole);
            $userRole->setRole($this);
        }

        return $this;
    }

    public function removeUserRole(UserRole $userRole): static { if ($this->userRoles->removeElement($userRole)) {
            // set the owning side to null (unless already changed)
            if ($userRole->getRole() === $this) {
                $userRole->setRole(null);
            }
        }

        return $this;
    }

    public function getIcon(): ?string {
        return $this->icon;
    }

    public function setIcon(?string $icon): static { $this->icon = $icon;

        return $this;
    }

    public function getRoleNameAlias(): ?string {
        return $this->role_name_alias;
    }

    public function setRoleNameAlias(string $role_name_alias): static {
        $this->role_name_alias = $role_name_alias;

        return $this;
    }

    public function isActive(): ?bool {
        return $this->is_active;
    }

    public function setIsActive(bool $is_active): static {
        $this->is_active = $is_active;

        return $this;
    }

    public function isDeleted(): ?bool {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): static {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    /**
     * @return Collection<int, RolePermission>
     */
    public function getRolePermissions(): Collection {
        return $this->rolePermissions;
    }

    public function addRolePermission(RolePermission $rolePermission): static {
        if (!$this->rolePermissions->contains($rolePermission)) {
            $this->rolePermissions->add($rolePermission);
            $rolePermission->setRole($this);
        }

        return $this;
    }

    public function removeRolePermission(RolePermission $rolePermission): static {
        if ($this->rolePermissions->removeElement($rolePermission)) {
            // set the owning side to null (unless already changed)
            if ($rolePermission->getRole() === $this) {
                $rolePermission->setRole(null);
            }
        }

        return $this;
    }

    public function getIconThumb(): ?string
    {
        return $this->icon_thumb;
    }

    public function setIconThumb(?string $icon_thumb): static
    {
        $this->icon_thumb = $icon_thumb;

        return $this;
    }
}

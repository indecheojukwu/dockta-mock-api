<?php

namespace App\Entity\Admin;

use App\Repository\Admin\PermissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, unique: false)]
    private ?string $name = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $type = null;

    #[ORM\Column(length: 50)]
    private ?string $module = null;

    #[ORM\Column(length: 50)]
    private ?string $action = null;

    #[ORM\Column]
    private ?bool $is_active = null;

    #[ORM\Column]
    private ?bool $is_deleted = null;

    /**
     * @var Collection<int, RolePermission>
     */
    #[ORM\OneToMany(targetEntity: RolePermission::class, mappedBy: 'permission')]
    private Collection $rolePermissions;

    /**
     * @var Collection<int, UserPermissionOverride>
     */
    #[ORM\OneToMany(targetEntity: UserPermissionOverride::class, mappedBy: 'permission')]
    private Collection $userPermissionOverrides;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    /**
     * @var Collection<int, PermissionAudit>
     */
    #[ORM\OneToMany(targetEntity: PermissionAudit::class, mappedBy: 'permission_granted')]
    private Collection $permissionAudits;

    public function __construct()
    {
        $this->rolePermissions = new ArrayCollection();
        $this->userPermissionOverrides = new ArrayCollection();
        $this->permissionAudits = new ArrayCollection();
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

    public function getType(): ?string {
        return $this->type;
    }

    public function setType(?string $type): static {
        $this->type = $type;

        return $this;
    }

    public function getModule(): ?string {
        return $this->module;
    }

    public function setModule(string $module): static {
        $this->module = $module;

        return $this;
    }

    public function getAction(): ?string {
        return $this->action;
    }

    public function setAction(string $action): static
    {
        $this->action = $action;

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

    public function isDeleted(): ?bool
    {
        return $this->is_deleted;
    }

    public function setIsDeleted(bool $is_deleted): static
    {
        $this->is_deleted = $is_deleted;

        return $this;
    }

    /**
     * @return Collection<int, RolePermission>
     */
    public function getRolePermissions(): Collection
    {
        return $this->rolePermissions;
    }

    public function addRolePermission(RolePermission $rolePermission): static
    {
        if (!$this->rolePermissions->contains($rolePermission)) {
            $this->rolePermissions->add($rolePermission);
            $rolePermission->setPermission($this);
        }

        return $this;
    }

    public function removeRolePermission(RolePermission $rolePermission): static
    {
        if ($this->rolePermissions->removeElement($rolePermission)) {
            // set the owning side to null (unless already changed)
            if ($rolePermission->getPermission() === $this) {
                $rolePermission->setPermission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserPermissionOverride>
     */
    public function getUserPermissionOverrides(): Collection
    {
        return $this->userPermissionOverrides;
    }

    public function addUserPermissionOverride(UserPermissionOverride $userPermissionOverride): static
    {
        if (!$this->userPermissionOverrides->contains($userPermissionOverride)) {
            $this->userPermissionOverrides->add($userPermissionOverride);
            $userPermissionOverride->setPermission($this);
        }

        return $this;
    }

    public function removeUserPermissionOverride(UserPermissionOverride $userPermissionOverride): static
    {
        if ($this->userPermissionOverrides->removeElement($userPermissionOverride)) {
            // set the owning side to null (unless already changed)
            if ($userPermissionOverride->getPermission() === $this) {
                $userPermissionOverride->setPermission(null);
            }
        }

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

    /**
     * @return Collection<int, PermissionAudit>
     */
    public function getPermissionAudits(): Collection
    {
        return $this->permissionAudits;
    }

    public function addPermissionAudit(PermissionAudit $permissionAudit): static
    {
        if (!$this->permissionAudits->contains($permissionAudit)) {
            $this->permissionAudits->add($permissionAudit);
            $permissionAudit->setPermissionGranted($this);
        }

        return $this;
    }

    public function removePermissionAudit(PermissionAudit $permissionAudit): static
    {
        if ($this->permissionAudits->removeElement($permissionAudit)) {
            // set the owning side to null (unless already changed)
            if ($permissionAudit->getPermissionGranted() === $this) {
                $permissionAudit->setPermissionGranted(null);
            }
        }

        return $this;
    }

}

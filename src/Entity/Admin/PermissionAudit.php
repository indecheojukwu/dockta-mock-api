<?php

namespace App\Entity\Admin;

use App\Entity\User;
use App\Repository\Admin\PermissionAuditRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PermissionAuditRepository::class)]
class PermissionAudit
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'permissionAudits')]
    #[ORM\JoinColumn(name: 'permission_granted', nullable: true)]
    private ?Permission $permission_granted = null;

    #[ORM\ManyToOne(inversedBy: 'permissionAudits')]
    #[ORM\JoinColumn(name: 'permission_denied', nullable: true)]
    private ?Permission $permission_denied = null;

    #[ORM\ManyToOne(inversedBy: 'permissionAudits')]
    #[ORM\JoinColumn(name: 'action_by', nullable: true)]
    private ?User $action_by = null;

    #[ORM\ManyToOne(inversedBy: 'targetUserPermissionAudits')]
    #[ORM\JoinColumn(name: 'target_user', nullable: false)]
    private ?User $target_user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPermissionGranted(): ?Permission
    {
        return $this->permission_granted;
    }

    public function setPermissionGranted(?Permission $permission_granted): static
    {
        $this->permission_granted = $permission_granted;

        return $this;
    }

    public function getPermissionDenied(): ?Permission
    {
        return $this->permission_denied;
    }

    public function setPermissionDenied(?Permission $permission_denied): static
    {
        $this->permission_denied = $permission_denied;

        return $this;
    }

    public function getActionBy(): ?User
    {
        return $this->action_by;
    }

    public function setActionBy(?User $action_by): static
    {
        $this->action_by = $action_by;

        return $this;
    }

    public function getTargetUser(): ?User
    {
        return $this->target_user;
    }

    public function setTargetUser(?User $target_user): static
    {
        $this->target_user = $target_user;

        return $this;
    }
}

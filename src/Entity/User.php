<?php

namespace App\Entity;

use App\Entity\Admin\PermissionAudit;
use App\Entity\Admin\RolePermission;
use App\Entity\Admin\UserPermissionOverride;
use App\Entity\Admin\UserRole;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ORM\Index(columns: ['person_id'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 50, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true, name: 'google_id')]
    private ?string $google_id = null;

    #[ORM\Column(type: 'text', length: 255, nullable: true, name: 'google_access_token')]
    private ?string $google_access_token = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $google_profile_pic_url = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $profile_pic_thumb = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $profile_pic_original = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phonenumber = null;

    #[ORM\OneToOne(inversedBy: 'user', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    #[ORM\Column(nullable: true)]
    private ?string $password = null;

    /**
     * @var Collection<int, UserRole>
     */
    #[ORM\OneToMany(targetEntity: UserRole::class, mappedBy: 'user')]
    private Collection $userRoles;

    /**
     * @var Collection<int, UserPermissionOverride>
     */
    #[ORM\OneToMany(targetEntity: UserPermissionOverride::class, mappedBy: 'user')]
    private Collection $userPermissionOverrides;

    /**
     * @var Collection<int, RolePermission>
     */
    #[ORM\OneToMany(targetEntity: RolePermission::class, mappedBy: 'granted_by')]
    private Collection $rolePermissions;

    /**
     * @var Collection<int, UserRole>
     */
    #[ORM\OneToMany(targetEntity: UserRole::class, mappedBy: 'granted_by')]
    private Collection $userGrantedRoles;

    /**
     * @var Collection<int, UserRole>
     */
    #[ORM\OneToMany(targetEntity: UserRole::class, mappedBy: 'revoked_by')]
    private Collection $userRevokedUserRoles;

    /**
     * @var Collection<int, PermissionAudit>
     */
    #[ORM\OneToMany(targetEntity: PermissionAudit::class, mappedBy: 'action_by')]
    private Collection $permissionAudits;

    /**
     * @var Collection<int, PermissionAudit>
     */
    #[ORM\OneToMany(targetEntity: PermissionAudit::class, mappedBy: 'target_user')]
    private Collection $targetUserPermissionAudits;

    /**
     * @var Collection<int, DoctorService>
     */
    #[ORM\OneToMany(targetEntity: DoctorService::class, mappedBy: 'doctor')]
    private Collection $doctorServices;

    /**
     * @var Collection<int, PatientService>
     */
    #[ORM\OneToMany(targetEntity: PatientService::class, mappedBy: 'user')]
    private Collection $patientServices;

    /**
     * @var Collection<int, Invoice>
     */
    #[ORM\OneToMany(targetEntity: Invoice::class, mappedBy: 'created_by')]
    private Collection $invoices;

    public function __construct() {
        $this->userRoles = new ArrayCollection();
        $this->userPermissionOverrides = new ArrayCollection();
        $this->rolePermissions = new ArrayCollection();
        $this->userGrantedRoles = new ArrayCollection();
        $this->userRevokedUserRoles = new ArrayCollection();
        $this->permissionAudits = new ArrayCollection();
        $this->targetUserPermissionAudits = new ArrayCollection();
        $this->doctorServices = new ArrayCollection();
        $this->patientServices = new ArrayCollection();
        $this->invoices = new ArrayCollection();
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getPerson(): ?Person {
        return $this->person;
    }

    public function setPerson(Person $person): static
    {
        $this->person = $person;

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

    public function getGoogleId(): ?string
    {
        return $this->google_id;
    }

    public function setGoogleId(?string $google_id): static
    {
        $this->google_id = $google_id;

        return $this;
    }

    public function getGoogleAccessToken(): ?string
    {
        return $this->google_access_token;
    }

    public function setGoogleAccessToken(?string $google_access_token): static
    {
        $this->google_access_token = $google_access_token;

        return $this;
    }

    public function getGoogleProfilePicUrl(): ?string
    {
        return $this->google_profile_pic_url;
    }

    public function setGoogleProfilePicUrl(?string $google_profile_pic_url): static
    {
        $this->google_profile_pic_url = $google_profile_pic_url;

        return $this;
    }

    public function getProfilePicThumb(): ?string
    {
        return $this->profile_pic_thumb;
    }

    public function setProfilePicThumb(?string $profile_pic_thumb): static
    {
        $this->profile_pic_thumb = $profile_pic_thumb;

        return $this;
    }

    public function getProfilePicOriginal(): ?string
    {
        return $this->profile_pic_original;
    }

    public function setProfilePicOriginal(?string $profile_pic_original): static
    {
        $this->profile_pic_original = $profile_pic_original;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string {
        return (string) $this->email;
    }

    public function getRoles(): array {
        $roles = $this->userRoles->map(fn(UserRole $user_role) => $user_role->getRole()->getName())->toArray();
        // guarantee every user at least has ROLE_USER
        /* $roles[] = 'ROLE_USER'; */

        return array_unique($roles);
    }

    public function getUserRoles(): Collection {
        return $this->userRoles;
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
            $userPermissionOverride->setUser($this);
        }

        return $this;
    }

    public function removeUserPermissionOverride(UserPermissionOverride $userPermissionOverride): static
    {
        if ($this->userPermissionOverrides->removeElement($userPermissionOverride)) {
            // set the owning side to null (unless already changed)
            if ($userPermissionOverride->getUser() === $this) {
                $userPermissionOverride->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RolePermission>
     */
    public function getRolePermissions(): Collection
    {
        return $this->rolePermissions;
    }

    public function addRolePermission(RolePermission $rolePermission): static {
        if (!$this->rolePermissions->contains($rolePermission)) {
            $this->rolePermissions->add($rolePermission);
            $rolePermission->setGrantedBy($this);
        }

        return $this;
    }

    public function removeRolePermission(RolePermission $rolePermission): static {
        if ($this->rolePermissions->removeElement($rolePermission)) {
            // set the owning side to null (unless already changed)
            if ($rolePermission->getGrantedBy() === $this) {
                $rolePermission->setGrantedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserRole>
     */
    public function getUserGrantedRoles(): Collection {
        return $this->userGrantedRoles;
    }

    public function addUserGrantedRole(UserRole $userGrantedRole): static {
        if (!$this->userGrantedRoles->contains($userGrantedRole)) {
            $this->userGrantedRoles->add($userGrantedRole);
            $userGrantedRole->setGrantedBy($this);
        }

        return $this;
    }

    public function removeUserGrantedRole(UserRole $userGrantedRole): static {
        if ($this->userGrantedRoles->removeElement($userGrantedRole)) {
            // set the owning side to null (unless already changed)
            if ($userGrantedRole->getGrantedBy() === $this) {
                $userGrantedRole->setGrantedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserRole>
     */
    public function getUserRevokedUserRoles(): Collection {
        return $this->userRevokedUserRoles;
    }

    public function addUserRevokedUserRole(UserRole $userRevokedUserRole): static {
        if (!$this->userRevokedUserRoles->contains($userRevokedUserRole)) {
            $this->userRevokedUserRoles->add($userRevokedUserRole);
            $userRevokedUserRole->setRevokedBy($this);
        }

        return $this;
    }

    public function removeUserRevokedUserRole(UserRole $userRevokedUserRole): static {
        if ($this->userRevokedUserRoles->removeElement($userRevokedUserRole)) {
            // set the owning side to null (unless already changed)
            if ($userRevokedUserRole->getRevokedBy() === $this) {
                $userRevokedUserRole->setRevokedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PermissionAudit>
     */
    public function getPermissionAudits(): Collection {
        return $this->permissionAudits;
    }

    public function addPermissionAudit(PermissionAudit $permissionAudit): static {
        if (!$this->permissionAudits->contains($permissionAudit)) {
            $this->permissionAudits->add($permissionAudit);
            $permissionAudit->setActionBy($this);
        }

        return $this;
    }

    public function removePermissionAudit(PermissionAudit $permissionAudit): static {
        if ($this->permissionAudits->removeElement($permissionAudit)) {
            // set the owning side to null (unless already changed)
            if ($permissionAudit->getActionBy() === $this) {
                $permissionAudit->setActionBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PermissionAudit>
     */
    public function getTargetUserPermissionAudits(): Collection {
        return $this->targetUserPermissionAudits;
    }

    public function addTargetUserPermissionAudit(PermissionAudit $targetUserPermissionAudit): static {
        if (!$this->targetUserPermissionAudits->contains($targetUserPermissionAudit)) {
            $this->targetUserPermissionAudits->add($targetUserPermissionAudit);
            $targetUserPermissionAudit->setTargetUser($this);
        }

        return $this;
    }

    public function removeTargetUserPermissionAudit(PermissionAudit $targetUserPermissionAudit): static {
        if ($this->targetUserPermissionAudits->removeElement($targetUserPermissionAudit)) {
            // set the owning side to null (unless already changed)
            if ($targetUserPermissionAudit->getTargetUser() === $this) {
                $targetUserPermissionAudit->setTargetUser(null);
            }
        }

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
            $doctorService->setDoctor($this);
        }

        return $this;
    }

    public function removeDoctorService(DoctorService $doctorService): static
    {
        if ($this->doctorServices->removeElement($doctorService)) {
            // set the owning side to null (unless already changed)
            if ($doctorService->getDoctor() === $this) {
                $doctorService->setDoctor(null);
            }
        }

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
            $patientService->setUser($this);
        }

        return $this;
    }

    public function removePatientService(PatientService $patientService): static
    {
        if ($this->patientServices->removeElement($patientService)) {
            // set the owning side to null (unless already changed)
            if ($patientService->getUser() === $this) {
                $patientService->setUser(null);
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
            $invoice->setCreatedBy($this);
        }

        return $this;
    }

    public function removeInvoice(Invoice $invoice): static
    {
        if ($this->invoices->removeElement($invoice)) {
            // set the owning side to null (unless already changed)
            if ($invoice->getCreatedBy() === $this) {
                $invoice->setCreatedBy(null);
            }
        }

        return $this;
    }

}

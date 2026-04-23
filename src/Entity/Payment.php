<?php

namespace App\Entity;

use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    use TimestampableEntity;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Invoice $invoice = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    /* (enum: cash, card, mobile_money, insurance) */
    #[ORM\Column(length: 20)]
    private ?string $payment_method = null;

    /* (enum: patient, insurance) */
    #[ORM\Column(length: 10)]
    private ?string $paid_by = null;

    #[ORM\Column(length: 15)]
    private ?string $transaction_reference = null;

    #[ORM\Column]
    private ?\DateTime $paid_at = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInvoice(): ?Invoice
    {
        return $this->invoice;
    }

    public function setInvoice(?Invoice $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): static
    {
        $this->amount = $amount;

        return $this;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->payment_method;
    }

    public function setPaymentMethod(string $payment_method): static
    {
        $this->payment_method = $payment_method;

        return $this;
    }

    public function getPaidBy(): ?string
    {
        return $this->paid_by;
    }

    public function setPaidBy(string $paid_by): static
    {
        $this->paid_by = $paid_by;

        return $this;
    }

    public function getTransactionReference(): ?string
    {
        return $this->transaction_reference;
    }

    public function setTransactionReference(string $transaction_reference): static
    {
        $this->transaction_reference = $transaction_reference;

        return $this;
    }

    public function getPaidAt(): ?\DateTime
    {
        return $this->paid_at;
    }

    public function setPaidAt(\DateTime $paid_at): static
    {
        $this->paid_at = $paid_at;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}

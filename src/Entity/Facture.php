<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?users $user = null;

    #[ORM\Column]
    private ?int $total = null;

    #[ORM\Column]
    private ?int $CardNumber = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $ExpiryDate = null;

    #[ORM\Column]
    private ?int $cvv = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $SubmissionDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?users
    {
        return $this->user;
    }

    public function setUser(?users $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTotal(): ?int
    {
        return $this->total;
    }

    public function setTotal(int $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getCardNumber(): ?int
    {
        return $this->CardNumber;
    }

    public function setCardNumber(int $CardNumber): static
    {
        $this->CardNumber = $CardNumber;

        return $this;
    }

    public function getExpiryDate(): ?\DateTimeInterface
    {
        return $this->ExpiryDate;
    }

    public function setExpiryDate(\DateTimeInterface $ExpiryDate): static
    {
        $this->ExpiryDate = $ExpiryDate;

        return $this;
    }

    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getSubmissionDate(): ?\DateTimeInterface
    {
        return $this->SubmissionDate;
    }

    public function setSubmissionDate(\DateTimeInterface $SubmissionDate): static
    {
        $this->SubmissionDate = $SubmissionDate;

        return $this;
    }
}

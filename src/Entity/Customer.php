<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Interface\IDable;
use App\Traits\IDScheme;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ApiResource]
class Customer implements IDable
{
    use IDScheme;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private string $taxCode;

    #[ORM\Column(length: 255)]
    private string $phone;

    #[ORM\Column(length: 255)]
    private string $email;

    #[ORM\Column(length: 255)]
    private string $address;

    #[ORM\OneToMany(targetEntity: Order::class, mappedBy: "customer")]
    private Collection $orders;

    #[ORM\OneToMany(targetEntity: QualityPreset::class, mappedBy: "customer")]
    private Collection $qualityPresets;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getTaxCode(): ?string
    {
        return $this->taxCode;
    }

    public function setTaxCode(string $taxCode): self
    {
        $this->taxCode = $taxCode;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

}
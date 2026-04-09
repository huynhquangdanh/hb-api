<?php

namespace App\Entity;

use App\Interface\IDable;
use App\Traits\IDScheme;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use App\State\OrderProcessor;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(processor: OrderProcessor::class),
        new Patch(),
        new Delete(),
    ]
)]

class Order implements IDable
{
    use IDScheme;

    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        $this->products = new ArrayCollection();
    }

    #[ApiProperty(writable: false)]
    #[ORM\Column(length: 255)]
    private ?string $manufacturingNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private DateTimeImmutable $orderDate;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private DateTimeImmutable $deliveryDate;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "orders")]
    private Customer $customer;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: "order")]
    private Collection $products;

    public function getManufacturingNumber(): ?string
    {
        return $this->manufacturingNumber;
    }

    public function setManufacturingNumber(string $manufacturingNumber): self
    {
        $this->manufacturingNumber = $manufacturingNumber;
        return $this;
    }

    public function getOrderDate(): ?DateTimeImmutable
    {
        return $this->orderDate;
    }

    public function setOrderDate(DateTimeImmutable $orderDate): self
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    public function getDeliveryDate(): ?DateTimeImmutable
    {
        return $this->deliveryDate;
    }

    public function setDeliveryDate(DateTimeImmutable $deliveryDate): self
    {
        $this->deliveryDate = $deliveryDate;
        return $this;
    }

}

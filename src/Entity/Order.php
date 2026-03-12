<?php

namespace App\Entity;

use App\Interface\IDable;
use App\Traits\IDScheme;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

class Order implements IDable
{
    use IDScheme;

    #[ORM\Column(length: 255)]
    private ?string $manufacturingNumber = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private DateTimeImmutable $orderDate;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private DateTimeImmutable $deliveryDate;

    /**
     * @var DateTimeImmutable[]|null
     */
    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $historyOfReleasing = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "orders")]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

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

    /**
     * @return DateTimeImmutable[]|null
     */
    public function getHistoryOfReleasing(): ?array
    {
        return $this->historyOfReleasing;
    }

    /**
     * @param DateTimeImmutable[]|null $historyOfReleasing
     */
    public function setHistoryOfReleasing(?array $historyOfReleasing): self
    {
        $this->historyOfReleasing = $historyOfReleasing;
        return $this;
    }
}

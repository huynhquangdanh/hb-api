<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Interface\IDable;
use App\Traits\IDScheme;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'releases')]
#[ApiResource]
class Release implements IDable
{
    use IDScheme;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, nullable: true)]
    private DateTimeImmutable $releaseDate;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private int $quantity;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: "releases")]
    private ?Product $product = null;

    public function __construct(DateTimeImmutable $releaseDate, int $quantity, Product $product)
    {
        $this->releaseDate = $releaseDate;
        $this->quantity = $quantity;
        $this->product = $product;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getReleaseDate(): DateTimeImmutable
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTimeImmutable $releaseDate): self
    {
        $this->releaseDate = $releaseDate;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;
        return $this;
    }
}
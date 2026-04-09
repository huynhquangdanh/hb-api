<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Interface\IDable;
use App\Traits\IDScheme;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;

#[ORM\Entity]
#[ORM\Table(name: 'products')]
#[ApiResource]
class Product implements IDable
{
    use IDScheme;

    public function __construct(string $name, string $size, int $quantity, Order $order, BoxType $boxType, QualityPreset $qualityPreset)
    {
        $this->name = $name;
        $this->size = $size;
        $this->quantity = $quantity;
        $this->order = $order;
        $this->boxType = $boxType;
        $this->qualityPreset = $qualityPreset;
        $this->releases = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $size = null;

    #[ORM\Column(type: Types::INTEGER)]
    private int $quantity;

    #[ORM\OneToMany(targetEntity: Release::class, mappedBy: "product")]
    private Collection $releases;

    #[ORM\ManyToOne(targetEntity: Order::class, inversedBy: "products")]
    private ?Order $order = null;

    #[ORM\ManyToOne(targetEntity: BoxType::class, inversedBy: "products")]
    private ?BoxType $boxType = null;

    #[ORM\OneToOne(targetEntity: QualityPreset::class, mappedBy: "product")]
    private ?QualityPreset $qualityPreset = null;

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getSize(): ?string
    {
        return $this->size;
    }

    public function setSize(string $size): self
    {
        $this->size = $size;
        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getOrder(): ?Order
    {
        return $this->order;
    }

    public function setOrder(?Order $order): self
    {
        $this->order = $order;
        return $this;
    }

    public function getBoxType(): ?BoxType
    {
        return $this->boxType;
    }

    public function setBoxType(?BoxType $boxType): self
    {
        $this->boxType = $boxType;
        return $this;
    }

    public function getReleases(): Collection
    {
        return $this->releases;
    }

    public function addRelease(Release $release): self
    {
        $this->releases->add($release);
        return $this;
    }

    public function removeRelease(Release $release): self
    {
        $this->releases->removeElement($release);
        return $this;
    }

    public function getQualityPreset(): ?QualityPreset
    {
        return $this->qualityPreset;
    }

    public function setQualityPreset(?QualityPreset $qualityPreset): self
    {
        $this->qualityPreset = $qualityPreset;
        return $this;
    }
}

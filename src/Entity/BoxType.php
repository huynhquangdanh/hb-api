<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Interface\IDable;
use App\Traits\IDScheme;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'box_types')]
#[ApiResource]
class BoxType implements IDable
{
    use IDScheme;

    #[ORM\Column(length: 255)]
    private string $name;

    // #[ORM\Column(length: 255)]
    // private int $numberOfLayers;

    #[ORM\Column(length: 255)]
    private string $calculationMethod;

    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: "boxType")]
    private ?Product $products = null;

    public function __construct(string $name, string $calculationMethod)
    {
        $this->name = $name;
        $this->calculationMethod = $calculationMethod;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    // public function getNumberOfLayers(): int
    // {
    //     return $this->numberOfLayers;
    // }

    // public function setNumberOfLayers(int $numberOfLayers): self
    // {
    //     $this->numberOfLayers = $numberOfLayers;
    //     return $this;
    // }

    public function getCalculationMethod(): string
    {
        return $this->calculationMethod;
    }

    public function setCalculationMethod(string $calculationMethod): self
    {
        $this->calculationMethod = $calculationMethod;
        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->products;
    }

    public function setProduct(?Product $product): self
    {
        $this->products = $product;
        return $this;
    }
}
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Interface\IDable;
use App\Traits\IDScheme;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity]
#[ORM\Table(name: 'paper_types')]
#[ApiResource]
class PaperType implements IDable
{
    use IDScheme;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(nullable: true)]
    private int $rollSize;

    #[ORM\Column(nullable: true)]
    private int $buster;

    #[ORM\Column(nullable: true)]
    private int $weight;

    #[ORM\ManyToOne(targetEntity: Provider::class)]
    private ?Provider $provider = null;

    public function __construct(string $name, int $rollSize, int $buster, int $weight, Provider $provider)
    {
        $this->name = $name;
        $this->rollSize = $rollSize;
        $this->buster = $buster;
        $this->weight = $weight;
        $this->provider = $provider;
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

    public function getRollSize(): string
    {
        return $this->rollSize;
    }

    public function setRollSize(string $rollSize): self
    {
        $this->rollSize = $rollSize;
        return $this;
    }

    public function getBuster(): string
    {
        return $this->buster;
    }

    public function setBuster(string $buster): self
    {
        $this->buster = $buster;
        return $this;
    }
}
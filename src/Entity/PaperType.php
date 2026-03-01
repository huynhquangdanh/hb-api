<?php

namespace App\Entity;

use App\Repository\PaperTypeRepository;
use Doctrine\ORM\Mapping as ORM;
#[ORM\Entity(repositoryClass: PaperTypeRepository::class)]
class PaperType
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'float')]
    private ?float $rollSize = null;

    #[ORM\Column(type: 'float')]
    private ?float $buster = null;

    #[ORM\ManyToOne(targetEntity: Provider::class, inversedBy: 'paperTypes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $provider = null;

    public function __construct()
    {
        $this->id = $this->id ?? \Symfony\Component\Uid\Uuid::v4()->toRfc4122();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getRollSize(): ?float
    {
        return $this->rollSize;
    }

    public function setRollSize(float $rollSize): self
    {
        $this->rollSize = $rollSize;
        return $this;
    }

    public function getBuster(): ?float
    {
        return $this->buster;
    }

    public function setBuster(float $buster): self
    {
        $this->buster = $buster;
        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;
        return $this;
    }
}

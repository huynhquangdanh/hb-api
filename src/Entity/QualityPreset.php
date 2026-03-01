<?php

namespace App\Entity;

use App\Repository\QualityPresetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QualityPresetRepository::class)]
class QualityPreset
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 36, unique: true)]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: 'qualityPresets')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

    #[ORM\ManyToOne(targetEntity: PaperType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaperType $facePaper = null;

    #[ORM\ManyToOne(targetEntity: PaperType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaperType $basePaper = null;

    #[ORM\ManyToOne(targetEntity: PaperType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaperType $flute = null;

    #[ORM\ManyToOne(targetEntity: PaperType::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?PaperType $assembly = null;

    /**
     * @var Collection<int, Product>
     */
    #[ORM\OneToMany(targetEntity: Product::class, mappedBy: 'preset')]
    private Collection $products;

    public function __construct()
    {
        $this->id = $this->id ?? \Symfony\Component\Uid\Uuid::v4()->toRfc4122();
        $this->products = new ArrayCollection();
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }

    public function getFacePaper(): ?PaperType
    {
        return $this->facePaper;
    }

    public function setFacePaper(?PaperType $facePaper): self
    {
        $this->facePaper = $facePaper;
        return $this;
    }

    public function getBasePaper(): ?PaperType
    {
        return $this->basePaper;
    }

    public function setBasePaper(?PaperType $basePaper): self
    {
        $this->basePaper = $basePaper;
        return $this;
    }

    public function getFlute(): ?PaperType
    {
        return $this->flute;
    }

    public function setFlute(?PaperType $flute): self
    {
        $this->flute = $flute;
        return $this;
    }

    public function getAssembly(): ?PaperType
    {
        return $this->assembly;
    }

    public function setAssembly(?PaperType $assembly): self
    {
        $this->assembly = $assembly;
        return $this;
    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }
}

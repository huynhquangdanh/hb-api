<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Interface\IDable;
use App\Traits\IDScheme;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'quality_presets')]
#[ApiResource]
class QualityPreset implements IDable
{
    use IDScheme;

    public function __construct(string $name, Customer $customer)
    {
        $this->name = $name;
        $this->customer = $customer;
        $this->flutePapers = new ArrayCollection();
        $this->assemblyPapers = new ArrayCollection();
    }

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\ManyToOne(targetEntity: PaperType::class)]
    private ?PaperType $facePaper = null;

    #[ORM\ManyToOne(targetEntity: PaperType::class)]
    private ?PaperType $basePapers = null;

    #[ORM\ManyToMany(targetEntity: PaperType::class)]
    #[ORM\JoinTable(name: 'quality_preset_flute_paper')]
    private Collection $flutePapers;

    #[ORM\ManyToMany(targetEntity: PaperType::class)]
    #[ORM\JoinTable(name: 'quality_preset_assembly_paper')]
    private Collection $assemblyPapers;

    #[ORM\OneToOne(targetEntity: Product::class, inversedBy: "qualityPreset")]
    private ?Product $product = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "qualityPresets")]
    private Customer $customer;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function getBasePapers(): ?PaperType
    {
        return $this->basePapers;
    }

    public function setBasePapers(?PaperType $basePapers): self
    {
        $this->basePapers = $basePapers;
        return $this;
    }

    public function getFlutePapers(): Collection
    {
        return $this->flutePapers;
    }

    public function addFlutePaper(PaperType $flutePaper): self
    {
        $this->flutePapers->add($flutePaper);
        return $this;
    }

    public function removeFlutePaper(PaperType $flutePaper): self
    {
        $this->flutePapers->removeElement($flutePaper);
        return $this;
    }

    public function getAssemblyPapers(): Collection
    {
        return $this->assemblyPapers;
    }

    public function addAssemblyPaper(PaperType $assemblyPaper): self
    {
        $this->assemblyPapers->add($assemblyPaper);
        return $this;
    }

    public function removeAssemblyPaper(PaperType $assemblyPaper): self
    {
        $this->assemblyPapers->removeElement($assemblyPaper);
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;
        return $this;
    }
}
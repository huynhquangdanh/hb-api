<?php

namespace App\Entity;

use App\Interface\IDable;
use App\Traits\IDScheme;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
class QualityPreset implements IDable
{
    use IDScheme;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $specifications = null;

    #[ORM\ManyToOne(targetEntity: Customer::class, inversedBy: "qualityPresets")]
    // #[ORM\JoinColumn(nullable: false)]
    private ?Customer $customer = null;

}
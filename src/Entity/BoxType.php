<?php

namespace App\Entity;

use App\Interface\IDable;
use App\Traits\IDScheme;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
class BoxType implements IDable
{
    use IDScheme;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(length: 255)]
    private int $numberOfLayers;

    #[ORM\Column(length: 255)]
    private string $calculationMethod;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private ?array $specifications = null;
}
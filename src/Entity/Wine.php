<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\WineRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WineRepository::class)]
class Wine extends Drink
{
    #[ORM\Column(length: 35, nullable: true)]
    private ?string $grape = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(nullable: true)]
    private ?float $alcohol_perc = null;

    public function getGrape(): ?string
    {
        return $this->grape;
    }

    public function setGrape(?string $grape): static
    {
        $this->grape = $grape;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getAlcoholPerc(): ?float
    {
        return $this->alcohol_perc;
    }

    public function setAlcoholPerc(?float $alcohol_perc): static
    {
        $this->alcohol_perc = $alcohol_perc;

        return $this;
    }
}

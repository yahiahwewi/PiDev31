<?php

namespace App\Entity;

use App\Repository\DonatorListRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DonatorListRepository::class)]
class DonatorList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $don_id = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDonId(): ?int
    {
        return $this->don_id;
    }

    public function setDonId(int $don_id): static
    {
        $this->don_id = $don_id;

        return $this;
    }
}

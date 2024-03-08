<?php

namespace App\Entity;

use App\Repository\DonationsListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DonationsListRepository::class)]
class DonationsList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private $id;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Please provide a date")]
    private $date;

    #[ORM\ManyToOne(targetEntity: Donator::class, inversedBy: "donationsLists")]
    #[ORM\JoinColumn(nullable: false, name: "donator_id_id", referencedColumnName: "id")]
    #[Assert\NotBlank(message: "Please select a donator")]
    private $donator_id_id;

    #[ORM\ManyToOne(targetEntity: Projects::class, inversedBy: "donationsLists")]
    #[ORM\JoinColumn(nullable: false, name: "project_id", referencedColumnName: "id")]
    private $project;

    #[ORM\Column(type: "integer")]
    #[Assert\NotBlank(message: "Please provide an amount")]
    #[Assert\Type(type: "integer", message: "The amount should be an integer")]
    private $montant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getDonatorIdId(): ?Donator
    {
        return $this->donator_id_id;
    }

    public function setDonatorIdId(?Donator $donator_id_id): self
    {
        $this->donator_id_id = $donator_id_id;

        return $this;
    }

    public function getProject(): ?Projects
    {
        return $this->project;
    }

    public function setProject(?Projects $project): self
    {
        $this->project = $project;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(?int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }
}

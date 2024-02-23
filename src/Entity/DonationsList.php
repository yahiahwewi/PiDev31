<?php


namespace App\Entity;

use App\Repository\DonationsListRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Donator; // Import de la classe Donator


#[ORM\Entity(repositoryClass: DonationsListRepository::class)]
class DonationsList
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'Please provide a date')]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'donationsLists')]
    #[ORM\JoinColumn(name: "donator_id_id", referencedColumnName: "id", nullable: false)]
    #[Assert\NotBlank(message: 'Please select a donator')]
    private ?Donator $donator_id_id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Please provide an amount')]
    #[Assert\Type(type: 'integer', message: 'The amount should be an integer')]
    private ?int $montant = null;

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

    public function setDonatorIdId(?Donator $donator): static
    {
        $this->donator_id_id = $donator;
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
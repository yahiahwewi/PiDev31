<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:2)]
    #[Assert\Length(max:20)]
    #[Assert\NotBlank (message:"veuillez saisir votre nom ")]
    #[Assert\Regex(
        pattern: '/^\D*$/',
        message: "LE NOM NE DOIT PAS CONTENIR DE NOMBRES."
    )]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:1)]
    #[Assert\Length(max:20)]
    #[Assert\Regex(
        pattern: '/^\D*$/',
        message: "LE PRENOM NE DOIT PAS CONTENIR DE NOMBRES."
    )]
    #[Assert\NotBlank (message:"veuillez saisir votre prénom  ")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Email(message: "Veuillez saisir une adresse email valide.")]

    private ?string $email = null;

    #[ORM\Column]
    #[Assert\Length(max:3)]
    #[Assert\Range(
        min: 16,
        notInRangeMessage: "Votre âge doit être de 16 ans ou plus.",
    )]
    #[Assert\NotBlank (message:"veuillez saisir votre age ")]
    private ?int $age = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Veuillez spécifier si le véhicule est motorisé.")]

    private ?bool $motorise = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Event $relation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function isMotorise(): ?bool
    {
        return $this->motorise;
    }

    public function setMotorise(bool $motorise): static
    {
        $this->motorise = $motorise;

        return $this;
    }

    public function getRelation(): ?Event
    {
        return $this->relation;
    }

public function setRelation(?Event $relation): static
{
    $this->relation = $relation;

    return $this;
}


}

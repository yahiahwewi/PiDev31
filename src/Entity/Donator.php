<?php

namespace App\Entity;

use App\Repository\DonatorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: DonatorRepository::class)]
class Donator
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $Prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $Email = null;

     #[ORM\Column(length: 255)]
     #[Assert\NotBlank]
    private ?string $Password = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $Montant = null; 


 
    private ?\DateTimeInterface $createdAt = null;
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
        return $this->Prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->Prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): static
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): static
    {
        $this->Password = $Password;

        return $this;
    }

    public function getMontant(): ?int
    {
        return $this->Montant;
    }
    
    public function setMontant(?int $Montant): self
    {
        $this->Montant = $Montant;
    
        return $this;
    }
    

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
    


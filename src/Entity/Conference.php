<?php

namespace App\Entity;


use App\Repository\ConferenceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert; 

#[ORM\Entity(repositoryClass: ConferenceRepository::class)]
#[Broadcast]
class Conference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

  

       #[ORM\Column(length: 255)]
    private $date;







    #[ORM\Column(length: 255)]
    private ?string $lieu = null;



    #[ORM\ManyToOne(inversedBy: 'conference')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Association $Association = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

  




    public function getAssociation(): ?Association
    {
        return $this->Association;
    }


    public function setAssociation(?Association $Association): self
    {
        $this->Association = $Association;


        return $this;
    }
}

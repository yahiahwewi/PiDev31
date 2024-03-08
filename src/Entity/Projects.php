<?php

namespace App\Entity;

use App\Repository\ProjectsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;


  #[ORM\Entity(repositoryClass :ProjectsRepository::class)]

class Projects
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type : 'integer')]
   
    private $id;

    
    #[ORM\Column(type :'string', length :255)]
    #[Assert\NotBlank]
   
    private $title;

    
     #[ORM\Column(type:'text')]
     #[Assert\NotBlank]
    
    private $description;

    #[ORM\Column(type: 'float')]
    #[Assert\NotBlank]
    private $amount;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Please provide a date")]
     
    private $startDate;

    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: "Please provide a date")]
     
    private $endDate;

    
     #[ORM\Column(type :'string', length : 255)]
     #[Assert\NotBlank(message: "Please provide a status")]
  
    private $status;

     #[ORM\OneToMany(targetEntity : DonationsList::class, mappedBy :"project")]
    private $donationsLists;

    public function __construct()
    {
        $this->donationsLists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status    = $status;

        return $this;
    }

    /**
     * @return Collection|DonationsList[]
     */
    public function getDonationsLists(): Collection
    {
        return $this->donationsLists;
    }

    public function addDonationsList(DonationsList $donationsList): self
    {
        if (!$this->donationsLists->contains($donationsList)) {
            $this->donationsLists[] = $donationsList;
            $donationsList->setProject($this);
        }

        return $this;
    }

    public function removeDonationsList(DonationsList $donationsList): self
    {
        if ($this->donationsLists->removeElement($donationsList)) {
            // set the owning side to null (unless already changed)
            if ($donationsList->getProject() === $this) {
                $donationsList->setProject(null);
            }
        }

        return $this;
    }
}

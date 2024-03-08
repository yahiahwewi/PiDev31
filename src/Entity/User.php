<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserTrait;



#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet e-mail est déjà utilisé.')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{

    #[ORM\Column(length: 255)]
    private $registrationDate;

    public function getRegistrationDate(): ?string
    {
        return $this->registrationDate;
    }

    public function setRegistrationDate(?string $registrationDate): self
    {
        $this->registrationDate = $registrationDate;

        return $this;
    }


    // use UserTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    
    // #[Assert\EqualTo(propertyPath: 'password', message: 'The passwords do not match.')]
// private ?string $confirmPassword = null;

// public function getConfirmPassword(): ?string
// {
//     return $this->confirmPassword;
// }

public function getUsername(): ?string
{
    return $this->email;
}
  /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $profileRoute;

    public function getProfileRoute(): ?string
    {
        return $this->profileRoute;
    }


public function getUserIdentifier(): ?string
{
    return (string) $this->id;
}


// public function setConfirmPassword(string $confirmPassword): static
// {
//     $this->confirmPassword = $confirmPassword;

//     return $this;
// }


    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?int $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    /**
     * @Assert\EqualTo(propertyPath="plainPassword", message="The passwords do not match.")
     */
    private ?string $confirmPassword = null;


    public function getConfirmPassword(): ?string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(?string $confirmPassword): self
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    // #[Assert\NotBlank(groups: ['registration'])]
    private ?string $plainPassword = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_gold = null;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(int $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getRoles(): array
    {
        return [$this->role];
    }

    public function getSalt(): ?string
    {
        // You can return null unless you are using a custom encoder
        return null;
    }

    public function eraseCredentials()
    {
        // Implement if you have any sensitive information that should be erased
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function isIsGold(): ?bool
    {
        return $this->is_gold;
    }

    public function setIsGold(?bool $is_gold): static
    {
        $this->is_gold = $is_gold;

        return $this;
    }
}

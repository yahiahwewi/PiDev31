<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'Cet e-mail est déjà utilisé.')]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
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
    return $this->name;
}

public function getUserIdentifier():  ?string
{
    return $this->id;
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
        // Implement logic to return user roles (e.g., ['ROLE_USER'])
        return ['ROLE_USER'];
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
}

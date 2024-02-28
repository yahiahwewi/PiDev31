<?php

namespace App\Entity;

use App\Repository\AdminRepository;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin implements UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;



    public function eraseCredentials()
    {
        // Implement if you have any sensitive information that should be erased
    }
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUsername(): ?string
    {
        return $this->email;
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

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }
    public function getRoles(): array
    {
        // Implement logic to return user roles (e.g., ['ROLE_USER'])
        return ['ROLE_ADMIN'];
    }

    public function getSalt(): ?string
    {
        // You can return null unless you are using a custom encoder
        return null;
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
    private $profileRoute;

    public function getProfileRoute(): ?string
    {
        return $this->profileRoute;
    }


public function getUserIdentifier(): ?string
{
    return (string) $this->id;
}
}

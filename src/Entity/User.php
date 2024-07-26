<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]

class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $UserName = null;

    #[ORM\Column(length: 255)]
    private ?string $Password = null;

    #[ORM\OneToOne(targetEntity: Rol::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Rol $rol = null;

    #[ORM\OneToOne(targetEntity: Email::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Email $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserName(): ?string
    {
        return $this->UserName;
    }

    public function setUserName(string $UserName): static
    {
        $this->UserName = $UserName;

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

    public function getRolId(): ?int
    {
        return $this->rol;
    }

    public function setRolId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getEmailId(): ?int
    {
        return $this->email;
    }

    public function setEmailId(int $id): static
    {
        $this->id = $id;

        return $this;
    }
}
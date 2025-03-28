<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[SerializedName("is_featured")]
    #[ORM\Column(nullable: true)]
    private ?bool $is_featured = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $created_at = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTime $delete_at = null;

    #[ORM\Column(type: 'integer', nullable: true)] 
    private ?int $totalPass = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function isFeatured(): ?bool
    {
        return $this->is_featured;
    }

    public function setFeatured(bool $is_featured): static
    {
        $this->is_featured = $is_featured;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDeleteAt(): ?\DateTime
    {
        return $this->delete_at;
    }

    public function setDeleteAt(\DateTime $delete_at): static
    {
        $this->delete_at = $delete_at;

        return $this;
    }

    public function getTotalPass(): ?int
    { 
        return $this->totalPass; 
    }
    
    public function setTotalPass(int $totalPass): static
    { 
        $this->totalPass = $totalPass; return $this; 
    }

    public function toArray(): array
    {
    return [
        'id' => $this->getId(),
        'name' => $this->getName(),
        'description' => $this->getDescription(),
        'image' => $this->getImage(),
        'is_featured' => $this->isFeatured(),
        'created_at' => $this->getCreatedAt() ? $this->getCreatedAt()->format('Y-m-d H:i:s') : null,
        'delete_at' => $this->getDeleteAt() ? $this->getDeleteAt()->format('Y-m-d H:i:s') : null,
        'total_pass' => $this->getTotalPass(),
    ];
    }
}

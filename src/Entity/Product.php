<?php

namespace App\Entity;

use App\DTO\ProductRequestDTO;
use App\DTO\ProductResponseDTO;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Table(name: 'products')]
#[ORM\Entity(repositoryClass: ProductRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?bool $isActive = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

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

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public static function fromDto(ProductRequestDTO $dto): self
    {
        $product = new self();
        $product->name = $dto->name;
        $product->price = $dto->price;
        $product->isActive = $dto->is_active;

        return $product;
    }

    public static function toDto(self $product): ProductResponseDTO
    {
        return new ProductResponseDTO(
            id: $product->getId(),
            name: $product->getName(),
            price: $product->getPrice(),
            is_active: $product->isActive(),
        );
    }
}

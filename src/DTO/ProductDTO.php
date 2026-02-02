<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'name is required')]
        #[Assert\Length(max: 255, maxMessage: 'name can\'t exceed 255 characters')]
        public readonly string $name,
        #[Assert\NotNull]
        #[Assert\GreaterThan(0)]
        public readonly float $price,
        #[Assert\NotNull]
        public readonly bool $is_active,
    ) {
    }
}
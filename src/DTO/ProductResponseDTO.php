<?php

namespace App\DTO;

class ProductResponseDTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $name,
        public readonly float $price,
        public readonly bool $is_active,
    ) {
    }
}

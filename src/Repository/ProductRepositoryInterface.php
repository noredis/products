<?php

namespace App\Repository;

use App\Entity\Product;

interface ProductRepositoryInterface
{
    public function findById(int $id): ?Product;
    public function save(Product $product): Product;
    public function delete(Product $product): void;
}

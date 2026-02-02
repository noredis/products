<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findById(int $id): ?Product
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function save(Product $product): Product
    {
        if ($product->getId() === null) {
            $this->getEntityManager()->persist($product);
        }

        $this->getEntityManager()->flush();

        return $product;
    }

    public function delete(Product $product): void
    {
        $this->getEntityManager()->remove($product);
        $this->getEntityManager()->flush();
    }
}

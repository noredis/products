<?php

namespace App\Controller;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_product', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ProductDTO $request,
        ProductRepositoryInterface $repository,
    ): JsonResponse
    {
        $product = Product::fromDto($request);

        $repository->save($product);

        return $this->json($product, 201);
    }
}

<?php

namespace App\Controller;

use App\DTO\ProductDTO;
use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductRepositoryInterface $repository,
    ) {
    }

    #[Route('/products', name: 'create-product', methods: ['POST'])]
    public function create(
        #[MapRequestPayload] ProductDTO $request,
    ): JsonResponse
    {
        $product = Product::fromDto($request);

        $this->repository->save($product);

        return $this->json($product, 201);
    }

    #[Route('/products/{id}', name: 'update-product', methods: ['PUT'], requirements: ['id' => '\d+'])]
    public function update(
        #[MapRequestPayload] ProductDTO $request,
        int $id,
    ): JsonResponse
    {
        $product = $this->findById($id);

        $product
            ->setName($request->name)
            ->setPrice($request->price)
            ->setIsActive($request->isActive);

        $this->repository->save($product);

        return $this->json($product, 200);
    }

    #[Route('/products/{id}', name: 'get-product', methods: ['GET'], requirements: ['id' => '\d+'])]
    public function index(
        int $id,
    ): JsonResponse
    {
        $product = $this->findById($id);
        return $this->json($product, 200);
    }

    #[Route('/products/{id}', name: 'delete-product', methods: ['DELETE'], requirements: ['id' => '\d+'])]
    public function delete(
        int $id,
    ): JsonResponse
    {
        $product = $this->findById($id);
        
        $this->repository->delete($product);

        return $this->json([], 204);
    }

    protected function findById(int $id): Product
    {
        $product = $this->repository->findById($id);
        if ($product === null) {
            throw new NotFoundHttpException('product not found');
        }

        return $product;
    }
}

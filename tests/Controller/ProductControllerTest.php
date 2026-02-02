<?php

namespace App\Tests\Controller;

use App\Controller\ProductController;
use App\DTO\ProductRequestDTO;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ProductControllerTest extends WebTestCase
{
    private ProductRepository $repository;
    private ProductController $controller;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProductRepository::class);
        $this->controller = new ProductController($this->repository);
    }

    public function testCreateProductSuccessfully(): void
    {
        $productDTO = new ProductRequestDTO(
            name: 'sugar 1kg',
            price: 60,
            is_active: true,
        );
        
        $expectedProduct = new Product();
        $expectedProduct->setName('sugar 1kg');
        $expectedProduct->setPrice(60);
        $expectedProduct->setIsActive(true);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(Product::class));

        $response = $this->controller->create($productDTO);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(201, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
    }

    public function testUpdateProductSuccessfully(): void
    {
        $productId = 1;
        $productDTO = new ProductRequestDTO(
            name: 'sugar 2kg',
            price: 120,
            is_active: false,
        );

        $existingProduct = new Product();
        $existingProduct->setName('sugar 1kg');
        $existingProduct->setPrice(60);
        $existingProduct->setIsActive(true);

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->repository
            ->expects($this->once())
            ->method('save')
            ->with($this->callback(function ($product) {
                return $product instanceof Product
                    && $product->getName() === 'sugar 2kg'
                    && $product->getPrice() === 120.0
                    && $product->isActive() === false;
            }));

        $response = $this->controller->update($productDTO, $productId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetProductSuccessfully(): void
    {
        $productId = 1;
        
        $existingProduct = new Product();
        $existingProduct->setName('sugar 1kg');
        $existingProduct->setPrice(60);
        $existingProduct->setIsActive(true);

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $response = $this->controller->index($productId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        
        $content = json_decode($response->getContent(), true);
        $this->assertIsArray($content);
    }

    public function testDeleteProductSuccessfully(): void
    {
        $productId = 1;
        
        $existingProduct = new Product();
        $existingProduct->setName('sugar 1kg');
        $existingProduct->setPrice(60);
        $existingProduct->setIsActive(true);

        $this->repository
            ->expects($this->once())
            ->method('findById')
            ->with($productId)
            ->willReturn($existingProduct);

        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($existingProduct);

        $response = $this->controller->delete($productId);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(204, $response->getStatusCode());
    }
}

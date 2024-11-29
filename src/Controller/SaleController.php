<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sale;
use App\Exception\InvalidArgumentException;
use App\Repository\SaleRepository;
use App\Service\ViolationsService;
use App\Service\SaleService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class SaleController extends AbstractController
{
    public function __construct(
        private SaleRepository $saleRepository,
        private SaleService $saleService,
    ) {}

    #[Route('/sales', name: 'app_sale_list', methods: ['GET'])]
    public function listSales(Request $request): JsonResponse
    {
        $validQueryFilters = $this->saleRepository->getFilters($request->query->all());
        if ($validQueryFilters) {
            return $this->json([
                'sales' => $this->saleRepository->findBy($validQueryFilters)
            ]);
        }

        return $this->json([
            'sales' => $this->saleRepository->findAll()
        ]);
    }

    #[Route('/sales/{sale}', name: 'app_sale_get', methods: ['GET'])]
    public function getSale(?Sale $sale): JsonResponse
    {
        if (!$sale) {
            return $this->json([
                'error' => 'Não há produto com esse identificador'
            ], 404);
        }

        return $this->json(
            $this->saleRepository->find($sale)
        );
    }

    #[Route('/sales', name: 'app_sale_create', methods: ['POST'])]
    public function createSale(Request $request): JsonResponse
    {
        try {
            $sale = $this->saleService->saveFromRequest($request);
        } catch (InvalidArgumentException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'error' => (new ViolationsService($e))->toArray()
            ], 400);
        }

        return $this->json($sale, 201, [
            'Location' => $request->getUri() . '/' . $sale->getId()
        ]);
    }

    #[Route('/sales/{sale}', name: 'app_sale_update', methods: ['PUT', 'PATCH'])]
    public function updateSale(?Sale $sale, Request $request): JsonResponse
    {
        if (!$sale) {
            return $this->json([
                'error' => 'Não há produto com esse identificador'
            ], 404);
        }

        try {
            $sale = $this->saleService->saveFromRequest($request, $sale);
        } catch (InvalidArgumentException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'error' => (new ViolationsService($e))->toArray()
            ], 400);
        }

        return $this->json($sale, 200);
    }

    #[Route('/sales/{sale}', name: 'app_sale_remove', methods: ['DELETE'])]
    public function removeSale(?Sale $sale): Response
    {
        if (!$sale) {
            return $this->json([
                'error' => 'Não há produto com esse identificador'
            ], 404);
        }

        $sale = $this->saleRepository->remove($sale, true);

        return new Response(status: 204);
    }
}

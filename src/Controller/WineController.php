<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\WineDto;
use App\Entity\Wine;
use App\Exception\InvalidArgumentException;
use App\Repository\WineRepository;
use App\Service\ViolationsService;
use App\Service\WineService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class WineController extends AbstractController
{
    public function __construct(
        private WineRepository $wineRepository,
        private WineService $wineService,
    ) {}

    #[Route('/wines', name: 'app_wine_list', methods: ['GET'])]
    public function listWines(): JsonResponse
    {
        return $this->json([
            'wines' => $this->wineRepository->findAll()
        ]);
    }

    #[Route('/wines/{wine}', name: 'app_wine_get', methods: ['GET'])]
    public function getWine(?Wine $wine): JsonResponse
    {
        if (!$wine) {
            return $this->json([
                'Error' => 'Não há produto com esse identificador'
            ], 404);
        }

        return $this->json(
            $this->wineRepository->find($wine)
        );
    }

    #[Route('/wines', name: 'app_wine_create', methods: ['POST'])]
    public function createWine(Request $request): JsonResponse
    {
        try {
            $wine = $this->wineService->saveFromRequest($request);
        } catch(InvalidArgumentException $e) {
            return $this->json([
                'Error' => $e->getMessage()
            ], 400);
        } catch(ValidationFailedException $e) {
            return $this->json([
                'Error' => (new ViolationsService($e))->toArray()
            ], 400);
        }

        return $this->json($wine, 201, [
            'Location' => $request->getUri() . '/' . $wine->getId()
        ]);
    }

    #[Route('/wines/{wine}', name: 'app_wine_update', methods: ['PUT', 'PATCH'])]
    public function updateWine(?Wine $wine, Request $request): JsonResponse
    {
        if (!$wine) {
            return $this->json([
                'Error' => 'Não há produto com esse identificador'
            ], 404);
        }

        try {
            $wine = $this->wineService->saveFromRequest($request, $wine);
        } catch(InvalidArgumentException $e) {
            return $this->json([
                'Error' => $e->getMessage()
            ], 400);
        } catch(ValidationFailedException $e) {
            return $this->json([
                'Error' => (new ViolationsService($e))->toArray()
            ], 400);
        }

        return $this->json($wine, 200);
    }

    #[Route('/wines/{wine}', name: 'app_wine_remove', methods: ['DELETE'])]
    public function removeWine(?Wine $wine): Response
    {
        if (!$wine) {
            return $this->json([
                'Error' => 'Não há produto com esse identificador'
            ], 404);
        }

        $wine = $this->wineRepository->remove($wine, true);

        return new Response(status: 204);
    }
}

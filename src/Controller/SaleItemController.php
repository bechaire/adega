<?php declare(strict_types=1); 

namespace App\Controller;

use App\Entity\Sale;
use App\Entity\SaleItem;
use App\Exception\InvalidArgumentException;
use App\Repository\SaleItemRepository;
use App\Service\SaleItemService;
use App\Service\ViolationsService;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

class SaleItemController extends AbstractController
{
    public function __construct(
        private NormalizerInterface $serializer,
        private SaleItemRepository $saleItemRepository,
        private SaleItemService $saleItemService,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/sales/{sale}/items', name: 'app_saleitems_get', methods: ['GET'])]
    public function getSaleItems(Sale $sale): JsonResponse
    {
        return $this->json(
            $this->saleItemRepository->fullSaleItemsData($sale->getId())
        );
    }
    
    #[Route('/sales/{sale}/items', name: 'app_saleitem_add', methods: ['POST'])]
    public function addSaleItem(?Sale $sale, Request $request): JsonResponse
    {
        if (!$sale) {
            return $this->json([
                'error' => 'Não há venda com esse identificador'
            ], 404);
        }

        try {
            $this->saleItemService->createFromRequest($request, $sale);
        } catch (InvalidArgumentException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'error' => (new ViolationsService($e))->toArray()
            ], 400);
        }

        return $this->json(
            $this->saleItemRepository->fullSaleItemsData($sale->getId()),
            201
        );
    }

    #[Route('/sales/{sale}/items/{saleItem}', name: 'app_saleitem_update', methods: ['PUT'])]
    public function updateSaleItem(?Sale $sale, ?SaleItem $saleItem, Request $request): JsonResponse
    {
        if (!$saleItem || !$sale || $sale->getId() != $saleItem->getSale()->getId()) {
            return $this->json([
                'error' => 'Não há venda com esse identificador'
            ], 404);
        }

        try {
            $this->saleItemService->updateFromRequest($request, $sale, $saleItem);
        } catch (InvalidArgumentException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        } catch (ValidationFailedException $e) {
            return $this->json([
                'error' => (new ViolationsService($e))->toArray()
            ], 400);
        }

        return $this->json(
            $this->saleItemRepository->fullSaleItemsData($sale->getId()),
            201
        );
    }

    #[Route('/sales/{sale}/items/{saleItem}', name: 'app_saleitem_get', methods: ['GET'])]
    public function getSaleItem(int $sale, int $saleItem): JsonResponse
    {
        // busca por dois parâmetros para garantir a integridade da requisição
        try {
            $saleItemData = $this->saleItemRepository->fullSaleItemData($sale, $saleItem);
        } catch(NoResultException) {
            return $this->json([
                'error' => 'Não há item de venda com esse identificador, verifique todos os identificadores informados na url'
            ], 404);
        }

        return $this->json(
            $saleItemData
        );
    }

    #[Route('/sales/{sale}/items/{saleItem}', name: 'app_saleitem_remove', methods: ['DELETE'])]
    public function removeSaleItem(?Sale $sale, ?SaleItem $saleItem): Response
    {
        // busca por dois parâmetros para garantir a integridade da requisição
        if (!$sale || !$saleItem) {
            return $this->json([
                'error' => 'Não há item de venda com esse identificador, verifique todos os identificadores informados na url'
            ], 404);
        }

        $sale->removeItem($saleItem);
        $this->em->flush();

        return new Response(status: 204);
    }
}

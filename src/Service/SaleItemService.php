<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\SaleItemDto;
use App\Entity\Drink;
use App\Entity\Sale;
use App\Entity\SaleItem;
use App\Exception\InvalidArgumentException;
use App\Repository\SaleItemRepository;
use App\Repository\WineRepository;
use App\Traits\RequestDataTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SaleItemService
{
    use RequestDataTrait;

    public function __construct(
        private SaleItemRepository $saleItemRepository,
        private WineRepository $wineRepository,
        private ValidatorInterface $validator,
        private EntityManagerInterface $em,
    ) {}


    public function createFromRequest(Request $request, Sale $sale): void
    {
        /** RequestDataTrait */
        $data = $this->getRequestData($request);

        // se é para adicionar apenas um registro, então crio o chave 'items' para poder iterar nele
        // e manter o código adequado para também quando vierem múltiplos registros
        if (!isset($data['items'])) {
            $data['items'] = [$data];
        }

        $drinkIds = array_column($data['items'], 'drinkId');
        if (!$this->wineRepository->validateIds($drinkIds)) {
            throw new InvalidArgumentException("Um ou mais IDs de produtos não foi localizado");
        }

        foreach ($data['items'] as $saleItemData) {
            $dto = $this->createDto($saleItemData);
            $drink = $this->wineRepository->find($saleItemData['drinkId']);
            
            $saleItem = new SaleItem();
            $saleItem->setSale($sale);
            $saleItem->setDrink($drink);
            $saleItem->setPrice($drink->getPrice());
            $saleItem->setWeightKg($drink->getWeightKg());

            $saleItem = $this->updateFromDto($saleItem, $dto);

            $sale->addItem($saleItem);
        }

        $this->em->flush();
    }

    /**
     * Cria um DTO a partir de um array de dados recebido, por um form HTML ou por um payload JSON,
     * em seguida faz a validação e lança eventuais excessões
     *
     * @param array $data Os dados recebidos do payload
     * @return SaleItemDto Retorna o DTO alimentado
     */
    private function createDto(array $data): SaleItemDto
    {
        $data = array_change_key_case($data, CASE_LOWER);

        // se os dados vierem parciais, considero os valores que já existem no objeto da entidade
        // a tipagem forçada (cast) objetiva evitar erros por corpo recebido com valores como string
        $dto = new SaleItemDto(
            (int) ($data['quantity'] ?? 0),
        );

        $violations = $this->validator->validate($dto);
        if (count($violations)) {
            throw new ValidationFailedException('errors', $violations);
        }

        return $dto;
    }

    /**
     * Atualiza um objeto Sale recebido a partir de um DTO alimentado e validado
     *
     * @param SaleItem $saleItem
     * @param SaleItemDto $saleItemDto
     * @return SaleItem Retorna o próprio objeto recebido para ações de aninhamento
     */
    public function updateFromDto(SaleItem $saleItem, SaleItemDto $saleItemDto): SaleItem
    {
        $saleItem->setQuantity($saleItemDto->quantity);
        return $saleItem;
    }
}

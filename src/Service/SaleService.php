<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\SaleDto;
use App\Entity\Sale;
use App\Repository\SaleRepository;
use App\Traits\RequestDataTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SaleService
{
    use RequestDataTrait;

    public function __construct(
        private SaleRepository $SaleRepository,
        private ValidatorInterface $validator
    ) {}

    /**
     * Faz a ação de salvamento (Add e Fetch) para um objeto Sale recebido ou o cria se vazio, 
     * os dados são coletados do request
     *
     * @param Request $request
     * @param Sale|null $Sale
     * @return Sale
     */
    public function saveFromRequest(Request $request, ?Sale $sale = null): Sale
    {
        /** RequestDataTrait */
        $data = $this->getRequestData($request);

        $isPatch = $request->getMethod() == 'PATCH';
        $dto = $this->createDto($data, $sale, $isPatch);

        if (!$sale) {
            $sale = new Sale();
        }

        $this->updateFromDto($sale, $dto);

        $this->SaleRepository->add($sale, true);

        return $sale;
    }

    /**
     * Cria um DTO a partir de um array de dados recebido, por um form HTML ou por um payload JSON,
     * em seguida faz a validação e lança eventuais excessões
     *
     * @param array $data Os dados recebidos do payload
     * @param Sale|null $sale Um objeto Sale alimentado para servir de valor default para uma ação de PATCH
     * @param boolean $isPatch Se a requisição é de PATCH, pode vir com campos obrigatórios faltando
     * @return SaleDto Retorna o DTO alimentado
     */
    private function createDto(array $data, ?Sale $sale, bool $isPatch): SaleDto
    {
        $data = array_change_key_case($data, CASE_LOWER);

        // se os dados vierem parciais, considero os valores que já existem no objeto da entidade
        // a tipagem forçada (cast) objetiva evitar erros por corpo recebido com valores como string
        $dto = new SaleDto(
            (int) ($data['distance'] ?? ($isPatch && $sale ? $sale->getDistance() : 0)),
            (string) ($data['date']  ?? ($isPatch && $sale ? $sale->getDate()->format('Y-m-d') : '')),
        );

        $violations = $this->validator->validate($dto);
        if (count($violations)) {
            throw new ValidationFailedException('Erros', $violations);
        }

        return $dto;
    }

    /**
     * Atualiza um objeto Sale recebido a partir de um DTO alimentado e validado
     *
     * @param Sale $sale
     * @param SaleDto $saleDto
     * @return Sale Retorna o próprio objeto recebido para ações de aninhamento
     */
    public function updateFromDto(Sale $sale, SaleDto $saleDto): Sale
    {
        $sale->setDistance($saleDto->distance);
        $sale->setDate(new \DateTime($saleDto->date));
        return $sale;
    }
}

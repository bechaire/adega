<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\WineDto;
use App\Entity\Wine;
use App\Repository\WineRepository;
use App\Traits\RequestDataTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class WineService
{
    use RequestDataTrait;

    public function __construct(
        private WineRepository $wineRepository,
        private ValidatorInterface $validator
    ) {}

    /**
     * Faz a ação de salvamento (Add e Fetch) para um objeto Wine recebido ou o cria se vazio, 
     * os dados são coletados do request
     *
     * @param Request $request
     * @param Wine|null $wine
     * @return Wine
     */
    public function saveFromRequest(Request $request, ?Wine $wine = null): Wine
    {
        /** RequestDataTrait */
        $data = $this->getRequestData($request);

        $isPatch = $request->getMethod() == 'PATCH';
        $dto = $this->createDto($data, $wine, $isPatch);

        if (!$wine) {
            $wine = new Wine();
        }

        $this->updateFromDto($wine, $dto);

        $this->wineRepository->add($wine, true);

        return $wine;
    }

    /**
     * Cria um DTO a partir de um array de dados recebido, ou por um form HTML ou por um payload JSON,
     * em seguida faz a validação e lança eventuais excessões
     *
     * @param array $data Os dados recebidos do payload
     * @param Wine|null $wine Um objeto Wine alimentado para servir de valor default para uma ação de PATCH
     * @param boolean $isPatch Se a requisição é de PATCH, pode vir com campos obrigatórios faltando
     * @return WineDto Retorna o DTO alimentado
     */
    private function createDto(array $data, ?Wine $wine, bool $isPatch): WineDto
    {
        $data = array_change_key_case($data, CASE_LOWER);

        // se os dados vierem parciais, considero os valores que já existem no objeto da entidade
        // a tipagem forçada (cast) objetiva evitar erros por corpo recebido com valores como string
        $dto = new WineDto(
            (string) $data['name']       ?? ($isPatch && $wine ? $wine->getName() : ''),
            (string) $data['grape']      ?? ($isPatch && $wine ? $wine->getGrape() : ''),
            (string) $data['country']    ?? ($isPatch && $wine ? $wine->getCountry() : ''),
            (float) $data['alcoholperc'] ?? ($isPatch && $wine ? $wine->getAlcoholPerc() : 0),
            (int) $data['volumeml']      ?? ($isPatch && $wine ? $wine->getVolumeMl() : 0),
            (float) $data['weightkg']    ?? ($isPatch && $wine ? $wine->getWeightKg() : 0),
            (int) $data['stock']         ?? ($isPatch && $wine ? $wine->getStock() : 0),
            (float) $data['price']       ?? ($isPatch && $wine ? $wine->getPrice() : 0)
        );

        $violations = $this->validator->validate($dto);
        if (count($violations)) {
            throw new ValidationFailedException('Erros', $violations);
        }

        return $dto;
    }

    /**
     * Atualiza um objeto Wine recebido a partir de um DTO alimentado e validado
     *
     * @param Wine $wine
     * @param WineDto $wineDto
     * @return Wine Retorna o próprio objeto recebido para ações de aninhamento
     */
    public function updateFromDto(Wine $wine, WineDto $wineDto): Wine
    {
        $wine->setName($wineDto->name);
        $wine->setGrape($wineDto->grape);
        $wine->setCountry($wineDto->country);
        $wine->setAlcoholPerc($wineDto->alcoholPerc);
        $wine->setVolumeMl($wineDto->volumeMl);
        $wine->setWeightKg($wineDto->weightKg);
        $wine->setStock($wineDto->stock);
        $wine->setPrice($wineDto->price);
        return $wine;
    }
}

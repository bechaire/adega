<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\WineDto;
use App\Entity\Wine;
use App\Exception\InvalidArgumentException;
use App\Repository\WineRepository;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class WineService
{
    public function __construct(
        private WineRepository $wineRepository,
        private ValidatorInterface $validator
    ) {}

    public function saveFromRequest(Request $request, ?Wine $wine = null): Wine
    {
        //aceitando json e html form submit
        if (str_starts_with($request->headers->get('Content-Type'), 'application/json')) {
            try {
                $data = $request->toArray();
            } catch (JsonException) {
                throw new InvalidArgumentException('Revise a estrutura do JSON submetido');
            }
        } else {
            $data = $request->request->all();
        }

        if (!$data) {
            throw new InvalidArgumentException('Não foi possível coletar os dados enviados, revise as informações');
        }

        $isPatch = $request->getMethod() == 'PATCH';
        $dto = $this->createDto($data, $wine, $isPatch);

        if (!$wine) {
            $wine = new Wine();
        }

        $this->updateFromDto($wine, $dto);

        $this->wineRepository->add($wine, true);

        return $wine;
    }

    public function createDto(array $data, ?Wine $wine, bool $isPatch): WineDto
    {
        $data = array_change_key_case($data, CASE_LOWER);

        // se os dados vierem parciais, considero os valores que já existem no objeto da entidade
        $dto = new WineDto(
            $data['name']        ?? ($isPatch && $wine ? $wine->getName() : ''),
            $data['grape']       ?? ($isPatch && $wine ? $wine->getGrape() : ''),
            $data['country']     ?? ($isPatch && $wine ? $wine->getCountry() : ''),
            $data['alcoholperc'] ?? ($isPatch && $wine ? $wine->getAlcoholPerc() : 0),
            $data['volumeml']    ?? ($isPatch && $wine ? $wine->getVolumeMl() : 0),
            $data['weightkg']    ?? ($isPatch && $wine ? $wine->getWeightKg() : 0),
            $data['stock']       ?? ($isPatch && $wine ? $wine->getStock() : 0),
            $data['price']       ?? ($isPatch && $wine ? $wine->getPrice() : 0)
        );

        $violations = $this->validator->validate($dto);
        if (count($violations)) {
            throw new ValidationFailedException('Erros', $violations);
        }

        return $dto;
    }

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

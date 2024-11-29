<?php declare(strict_types=1); 

namespace App\Traits;

use App\Exception\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Exception\JsonException;
use Symfony\Component\HttpFoundation\Request;

trait RequestDataTrait
{
    /**
     * O objetivo deste método é coletar as informações do request quando um POST é realizado, buscando 
     * dados do "FormData/HTML" ou no corpo da requisição, em formato JSON
     *
     * @param Request $request
     * @return array Dados compilados de acordo como feita a entrada
     */
    public function getRequestData(Request $request): array
    {
        // aceitando json e html form submit
        if (str_starts_with($request->headers->get('Content-Type') ?? '', 'application/json')) {
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

        return $data;
    }
}
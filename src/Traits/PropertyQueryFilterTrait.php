<?php

declare(strict_types=1);

namespace App\Traits;

use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

/**
 * A ideia aqui é pegar todas as query params da url (?grape=Merlot&volumeMl=600&country=Italy)
 * e parear com os parâmetros da entidade do repository para aceitar ou rejeitar o filtro,
 * evitando erros (apesar de que, lançar uma excessão pode ser mais educativo neste caso)
 */
trait PropertyQueryFilterTrait
{
    public function getFilters(array $query): array
    {
        // pega as propriedades da classe atual
        $reflect = new \ReflectionClass($this->getEntityName());
        $properties = [...$reflect->getProperties(), ...$reflect->getParentClass()->getProperties()];

        // pega os nomes das props que podem ser filtradas
        $propertyNames = array_flip(array_map(
            fn($prop) => $prop->getName(),
            $properties
        ));

        // TODO: não implementada forma de aferir o tipo e aplicar de acordo (fazer reflexão da propriedade)
        unset($propertyNames['created_at']);
        unset($propertyNames['updated_at']);

        // transforma as query params names em snake_case e remove o que não for propriedade
        return $this->normalizeAndFilterQueryParams($query, $propertyNames);
    }

    private function normalizeAndFilterQueryParams($query, $propertyNames)
    {
        $normalizer = new CamelCaseToSnakeCaseNameConverter();
        $queryNames = array_keys($query);
        foreach ($queryNames as $key) {
            if (array_key_exists($key, $propertyNames)) {
                continue;
            }
            $kSnake = $normalizer->normalize($key);
            if (array_key_exists($kSnake, $propertyNames)) {
                $query[$kSnake] = $query[$key];
            }
            unset($query[$key]);
        }

        return $query;
    }
}

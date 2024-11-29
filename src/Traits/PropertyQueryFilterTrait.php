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
    /**
     * A partir dos parâmetros de uma query string filtra o que tiver relação com a entidade em associação/uso
     *
     * @param array $query Conteúdo da query string em array
     * @return array Retorna os campos da query que tem relação com campos da entidade, ignorando o que 
     * estiver "sobrando"
     */
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

    /**
     * Faz o filtro dos query params removendo o que não existir com aquele nome como uma propriedade da entidade
     *
     * @param array $query Query string recebida em formato de array
     * @param array $propertyNames Nomes das propriedades da entidade mapeada
     * @return array Query params que de fato podem ser filtrados
     */
    private function normalizeAndFilterQueryParams(array $query, array $propertyNames): array
    {
        // Converte de createdAt para created_at, por exemplo
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

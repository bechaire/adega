<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sale;
use App\Traits\PropertyQueryFilterTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sale>
 */
class SaleRepository extends ServiceEntityRepository
{
    use PropertyQueryFilterTrait;

    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Sale::class);
    }

    /**
     * Faz a busca dos dados da venda apenas, sem os itens (N+1)
     *
     * @param array $filter
     * @return Sale
     */
    public function findSalesWithoutItems(array $filter=[]): array
    {
        $buider = $this->getEntityManager()->createQueryBuilder();
        
        $buider->select('sale')
               ->from(Sale::class, 'sale')
               ->orderBy('sale.id', 'DESC');

        foreach($filter as $field => $value) {
            if (is_null($value)) {
                continue;
            }
            $buider->andWhere("sale.{$field} = :{$field}")
                    ->setParameter($field, $value);
        }

        return $buider->getQuery()->getArrayResult();
    }

    public function add(Sale $sale, bool $flush = false): void
    {
        $this->getEntityManager()->persist($sale);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sale $sale, bool $flush = false): void
    {
        $this->getEntityManager()->remove($sale);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * Hidrata um array associativo com os dados de uma venda, adicionando a coluna 'items'
     * com as informações principais dos itens comprados
     *
     * @param array $saleData
     * @return array
     */
    public function hydrateWithSaleItems(array $saleData): array
    {
        $dql = <<<DQL
            SELECT item.id, item.price, item.quantity, 
                   drink.id as drink_id, drink.name, drink.volume_ml as volume, drink.weight_kg as weight,
                   item.created_at, item.updated_at
            FROM App\Entity\SaleItem item
            LEFT JOIN item.drink drink
            WHERE item.sale = :sale_id
            ORDER BY item.id 
        DQL;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('sale_id', $saleData['id'] ?? 0);

        $saleData['items'] = $query->getArrayResult();
        
        return $saleData;
    }
}

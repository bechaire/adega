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

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sale::class);
    }

    /**
     * Faz a busca dos dados da venda apenas, sem os itens (N+1)
     *
     * @param array $filter
     * @return Sale
     */
    public function findSalesOnly(array $filter=[]): array
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
}

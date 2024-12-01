<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SaleItem;
use App\Traits\PropertyQueryFilterTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SaleItem>
 */
class SaleItemRepository extends ServiceEntityRepository
{
    use PropertyQueryFilterTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SaleItem::class);
    }

    public function add(SaleItem $saleItem, bool $flush = false): void
    {
        $this->getEntityManager()->persist($saleItem);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SaleItem $saleItem, bool $flush = false): void
    {
        $this->getEntityManager()->remove($saleItem);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function fullSaleItemData(int $saleId, int $saleItemId): array
    {
        $dql = <<<DQL
            SELECT item.id, item.price, item.quantity, 
                   drink.id as drink_id, drink.name, drink.volume_ml as volume, drink.weight_kg as weight,
                   item.created_at, item.updated_at
            FROM App\Entity\SaleItem item
            LEFT JOIN item.drink drink
            WHERE item.sale = :sale
            AND item.id = :saleitem
            ORDER BY item.id 
        DQL;

        $query = $this->getEntityManager()->createQuery($dql);
        $query->setParameter('sale', $saleId);
        $query->setParameter('saleitem', $saleItemId);
        
        return $query->getSingleResult();
    }
}

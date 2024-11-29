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

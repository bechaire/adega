<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Wine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wine>
 */
class WineRepository extends ServiceEntityRepository
{
    public function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Wine::class);
    }

    public function add(Wine $wine, bool $flush = false): void
    {
        $this->getEntityManager()->persist($wine);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Wine $wine, bool $flush = false): void
    {
        
        // $wine = $this->getEntityManager()->getReference(Wine::class, $id);
        
        $this->getEntityManager()->remove($wine);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

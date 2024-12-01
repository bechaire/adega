<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Drink;
use App\Entity\Wine;
use App\Traits\PropertyQueryFilterTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Wine>
 */
class WineRepository extends ServiceEntityRepository
{
    use PropertyQueryFilterTrait;

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

    public function validateIds(array $ids): bool
    {
        $buider = $this->getEntityManager()->createQueryBuilder();

        $ids = array_unique($ids);

        $ids = array_map(fn($id) => (int) $id, $ids);

        $inIds = implode(',', $ids);

        $buider->select('drink')
               ->from(Drink::class, 'drink')
               ->where("drink.id in ({$inIds})");

        $queryResult = $buider->getQuery()->getArrayResult();

        return count($queryResult) == count($ids);
    }
}

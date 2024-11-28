<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Wine;
use App\Traits\PropertyQueryFilterTrait;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;

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
}

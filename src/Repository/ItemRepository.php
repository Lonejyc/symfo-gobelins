<?php

namespace App\Repository;

use App\Entity\Item;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Item>
 */
class ItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Item::class);
    }

    public function findClosestItemByPrice(float $targetPrice): ?Item
    {
        return $this->createQueryBuilder('i')
            ->orderBy('ABS(i.basePrice - :target)', 'ASC')
            ->setParameter('target', $targetPrice)
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserSubscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserSubscription>
 */
class UserSubscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSubscription::class);
    }

    /**
     * Trouve le dernier abonnement actif (ou dont l'expiration est la plus tardive) pour un utilisateur.
     * Utilisé pour empiler la durée des nouveaux abonnements.
     * * @param User $user
     * @return UserSubscription|null
     */
    public function findLastActiveSubscription(User $user): ?UserSubscription
    {
        $now = new \DateTimeImmutable();

        return $this->createQueryBuilder('us')
            ->andWhere('us.user = :user')
            ->andWhere('us.endDate >= :now')
            ->setParameter('user', $user)
            ->setParameter('now', $now)
            ->orderBy('us.endDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Subscription;
use App\Entity\User;
use App\Entity\UserSubscription;
use App\Repository\UserSubscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use \DateTimeImmutable;

class SubscriptionPurchaseProcessor implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private UserSubscriptionRepository $userSubscriptionRepository
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour acheter un abonnement.');
        }

        /** @var Subscription $subscription */
        $subscription = $data;
        $price = $subscription->getPrice();

        // 1. Vérification du solde
        if ($price > $user->getBalance()) {
            throw new \InvalidArgumentException("Solde insuffisant pour acheter l'abonnement.");
        }

        // 2. Déduction du prix
        $user->setBalance($user->getBalance() - $price);

        // 3. Gestion de la durée (1 mois par achat)
        $durationMonths = $subscription->getDurationMonths();
        $now = new DateTimeImmutable();

        // Trouver la date de fin actuelle (pour l'empilement)
        $lastSubscription = $this->userSubscriptionRepository->findLastActiveSubscription($user);

        $startDate = $now;
        if ($lastSubscription && $lastSubscription->getEndDate() > $now) {
            // Si un abonnement est actif, on ajoute la durée après sa fin actuelle
            $startDate = $lastSubscription->getEndDate();
        }

        $endDate = $startDate->modify("+$durationMonths month");

        // 4. Création de l'entrée d'abonnement
        $userSubscription = new UserSubscription();
        $userSubscription->setUser($user);
        $userSubscription->setSubscription($subscription);
        $userSubscription->setStartDate($startDate);
        $userSubscription->setEndDate($endDate);

        $this->em->persist($userSubscription);

        // 5. Mise à jour du Tier de l'utilisateur (optionnel, mais utile pour les Voters)
        $user->setTier($subscription->getTier());
        $this->em->persist($user);

        $this->em->flush();

        return $userSubscription;
    }
}

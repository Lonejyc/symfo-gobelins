<?php

namespace App\State;

use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Repository\InventoryItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use ApiPlatform\Metadata\Operation;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class InventorySellAllProcessor implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private InventoryItemRepository $inventoryItemRepository
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var User|null $user */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté.');
        }

        $itemsToSell = $this->inventoryItemRepository->findBy(['owner' => $user]);

        if (empty($itemsToSell)) {
            throw new \Exception("Votre inventaire est déjà vide.");
        }

        $totalGained = 0.0;

        foreach ($itemsToSell as $item) {
            $totalGained += $item->getCalculatedPrice();
            $this->em->remove($item);
        }

        $user->setBalance($user->getBalance() + $totalGained);
        $this->em->persist($user);

        $this->em->flush();

        return $user;
    }

//    public function supports(mixed $data, Operation $operation, array $uriVariables = []): bool
//    {
//        return $data === null && $operation->getName() === 'sell_all_items';
//    }
}

<?php

namespace App\State;

use ApiPlatform\State\ProcessorInterface;
use App\Entity\InventoryItem;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use ApiPlatform\Metadata\Operation;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class InventorySellProcessor implements ProcessorInterface
{

    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $itemToSell = $data;

        /** @var User|null $user */
        $user = $this->security->getUser();

        if (!$user || $itemToSell->getOwner() !== $user) {
            throw new AccessDeniedException('Vous ne pouvez pas vendre cet item.');
        }

        $price = $itemToSell->getCalculatedPrice();

        $user->setBalance($user->getBalance() + $price);

        $this->em->remove($itemToSell);

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

//    public function supports(mixed $data, Operation $operation, array $uriVariables = []): bool
//    {
//        return $data instanceof InventoryItem && $operation->getName() === 'sell_item';
//    }
}

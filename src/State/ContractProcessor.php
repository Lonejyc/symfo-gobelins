<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\ContractRequest;
use App\Entity\InventoryItem;
use App\Entity\User;
use App\Repository\InventoryItemRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class ContractProcessor implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        private EntityManagerInterface $em,
        private InventoryItemRepository $inventoryItemRepository,
        private ItemRepository $itemRepository
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var ContractRequest $dto */
        $dto = $data;

        /** @var User|null $user */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour faire un contrat.');
        }

        $inventoryItems = $this->inventoryItemRepository->findBy(['id' => $dto->inventoryItemIds]);

        if (count($inventoryItems) !== 10) {
            throw new \InvalidArgumentException("Impossible de trouver les 10 items spécifiés ou doublons détectés.");
        }

        $totalValue = 0.0;

        foreach ($inventoryItems as $invItem) {
            if ($invItem->getOwner() !== $user) {
                throw new AccessDeniedException("L'item #{$invItem->getId()} ne vous appartient pas.");
            }
            $totalValue += $invItem->getCalculatedPrice();
        }

        $minVal = $totalValue / $dto->riskFactor;
        $maxVal = $totalValue * $dto->riskFactor;

        $randomMultiplier = (float) mt_rand() / (float) mt_getrandmax();
        $targetPrice = $minVal + ($randomMultiplier * ($maxVal - $minVal));

        $wonItem = $this->itemRepository->findClosestItemByPrice($targetPrice);

        if (!$wonItem) {
            throw new \RuntimeException("Aucun item trouvé dans la base de données.");
        }

        foreach ($inventoryItems as $invItem) {
            $this->em->remove($invItem);
        }

        $newInventoryItem = new InventoryItem();
        $newInventoryItem->setOwner($user);
        $newInventoryItem->setItem($wonItem);
        $newInventoryItem->setAcquiredAt(new \DateTimeImmutable());

        $newInventoryItem->setFloat((float) mt_rand(0, 999999) / 1000000);
        $newInventoryItem->setStatTrak(mt_rand(1, 100) <= 10);

        $this->em->persist($newInventoryItem);
        $this->em->flush();

        return $newInventoryItem;
    }
}

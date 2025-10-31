<?php

namespace App\State;

use ApiPlatform\State\ProcessorInterface;
use App\Entity\InventoryItem;
use App\Entity\Kase;
use App\Entity\User;
use App\Enum\UserTier;
use App\Repository\KaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use ApiPlatform\Metadata\Operation;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class KaseOpenerProcessor implements ProcessorInterface
{
    private const STATTRAK_CHANCE = 10;
    public function __construct(
        private KaseRepository $kaseRepository,
        private Security $security,
        private EntityManagerInterface $em,
    )
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        /** @var User $user */
        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('Vous devez être connecté pour ouvrir une caisse.');
        }

        /** @var Kase $kase */
        $kase = $this->kaseRepository->find($uriVariables['id']);

        if (!$kase) {
            throw new \InvalidArgumentException("Caisse non trouvée.");
        }

        $getTierValue = function (UserTier $tier): int {
            return match($tier) {
                UserTier::BASIC => 1,
                UserTier::GOLD => 2,
                UserTier::DIAMOND => 3,
                default => 0,
            };
        };

        $userTierValue = $getTierValue($user->getTier());
        $caseTierValue = $getTierValue($kase->getRequiredTier());

        // 0. Check if the user has the correct tier to open the case
        if ($userTierValue < $caseTierValue) {
            throw new \InvalidArgumentException("Le tier '" . $kase->getRequiredTier()->value . "' est requis pour ouvrir cette caisse.");
        }

        // 1. Check if the user can afford the case
        if ($kase->getPrice() > $user->getBalance()) {
            throw new \InvalidArgumentException("Solde insuffisant pour ouvrir cette caisse.");
        }

        // 2. Deduct the case price from the user's balance
        $user->setBalance($user->getBalance() - $kase->getPrice());
        $this->em->persist($user);

        // 3. Randomly select an item from the case
        $caseItems = $kase->getCaseItems()->toArray();

        if (empty($caseItems)) {
            throw new \Exception("Cette caisse est vide et ne peut pas être ouverte.");
        }

        $roll = (float) mt_rand() / (float) mt_getrandmax();
        $cumulativeDropRate = 0.0;

        $wonCaseItem = null;

        foreach ($caseItems as $caseItem) {
            $cumulativeDropRate += $caseItem->getDropRate();
            if ($roll <= $cumulativeDropRate) {
                $wonCaseItem = $caseItem;
                break;
            }
        }

        if (!$wonCaseItem) {
            $wonCaseItem = $caseItems[count($caseItems) - 1];
        }

        // 4. Add the item to the user's inventory
        $isStatTrak = (mt_rand(1, 100) <= self::STATTRAK_CHANCE);

        $floatValue = (float) mt_rand(0, 999999) / 1000000;

        $newInventoryItem = new InventoryItem();
        $newInventoryItem->setOwner($user);
        $newInventoryItem->setItem($wonCaseItem->getItem());
        $newInventoryItem->setFloat($floatValue);
        $newInventoryItem->setStatTrak($isStatTrak);
        $newInventoryItem->setAcquiredAt(new \DateTimeImmutable());

        $this->em->persist($newInventoryItem);

        // 5. Save changes
        $this->em->flush();

        return $newInventoryItem;
    }
}

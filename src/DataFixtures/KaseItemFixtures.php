<?php

namespace App\DataFixtures;

use App\Entity\Item;
use App\Entity\Kase;
use App\Entity\KaseItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class KaseItemFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            [2, 1, 16, 0.001],
            [3, 1, 15, 0.002],
            [5, 1, 3, 0.25],
            [4, 1, 1, 0.507],
            [7, 1, 4, 0.2],
            [1, 1, 9, 0.04],
            [8, 3, 5, 0.7992],
            [9, 3, 6, 0.0532],
            [10, 3, 7, 0.0533],
            [11, 3, 8, 0.0533],
            [12, 3, 11, 0.0106],
            [13, 3, 9, 0.0107],
            [14, 3, 10, 0.0107],
            [15, 3, 14, 0.0032],
            [16, 3, 13, 0.0032],
            [17, 3, 15, 0.0026],
        ];

        foreach ($data as [$id, $kaseId, $itemId, $dropRate]) {
            $kaseItem = new KaseItem();
            $kaseItem->setDropRate((float)$dropRate);

            $kase = $this->getReference(
                KaseFixtures::KASE_REFERENCE . $kaseId,
                Kase::class
            );
            $kaseItem->setCase($kase);

            $item = $manager->getReference(Item::class, $itemId);
            $kaseItem->setItem($item);

            $manager->persist($kaseItem);
        }

        $manager->flush();
    }

    // Cette méthode dit à Symfony : "Lance KaseFixtures AVANT moi"
    public function getDependencies(): array
    {
        return [
            KaseFixtures::class,
            // Si tu as une classe ItemFixtures, ajoute-la ici aussi :
            // ItemFixtures::class,
        ];
    }
}

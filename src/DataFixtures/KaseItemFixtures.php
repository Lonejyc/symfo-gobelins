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
            [1, 1, 1, 0.507],
            [2, 1, 16, 0.001],
            [3, 1, 15, 0.002],
            [4, 1, 9, 0.04],
            [5, 1, 3, 0.25],
            [6, 1, 4, 0.2],
            [7, 1, 15, 0.0026],
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
            [18, 2, 5, 0.55],
            [19, 2, 7, 0.25],
            [20, 2, 6, 0.10],
            [21, 2, 11, 0.05],
            [22, 2, 9, 0.03],
            [23, 2, 14, 0.01],
            [24, 2, 13, 0.005],
            [25, 2, 10, 0.005],
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

    public function getDependencies(): array
    {
        return [
            KaseFixtures::class,
            ItemFixtures::class,
        ];
    }
}

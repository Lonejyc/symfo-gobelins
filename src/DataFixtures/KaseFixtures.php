<?php

namespace App\DataFixtures;

use App\Entity\Kase;
use App\Enum\UserTier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class KaseFixtures extends Fixture
{
    public const KASE_REFERENCE = 'kase_';

    public function load(ObjectManager $manager): void
    {
        $data = [
            [1, 'Red Case', 1.6, '/images/case_red.png', 'basic'],
            [2, 'Blue Case', 3.1, '/images/case_blue.png', 'gold'],
            [3, 'Fade Case', 8.0, '/images/case_fade.png', 'basic'],
        ];

        foreach ($data as [$id, $name, $price, $image, $tierStr]) {
            $kase = new Kase();
            $kase->setName($name);
            $kase->setPrice((float)$price);
            $kase->setImageUrl($image);

            $tier = match ($tierStr) {
                'gold' => UserTier::GOLD,
                'diamond' => UserTier::DIAMOND,
                default => UserTier::BASIC,
            };
            $kase->setRequiredTier($tier);

            $manager->persist($kase);

            $this->addReference(self::KASE_REFERENCE . $id, $kase);
        }

        $manager->flush();
    }
}

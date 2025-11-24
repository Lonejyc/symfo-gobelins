<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Enum\UserTier;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SubscriptionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // --- Plan GOLD ---
        $gold = new Subscription();
        $gold->setName('Gold Member');
        $gold->setPrice(9.99);
        $gold->setTier(UserTier::GOLD);
        $gold->setDurationMonths(1);

        $manager->persist($gold);

        $this->addReference('subscription_gold', $gold);

        // --- Plan DIAMOND ---
        $diamond = new Subscription();
        $diamond->setName('Diamond Elite');
        $diamond->setPrice(19.99);
        $diamond->setTier(UserTier::DIAMOND);
        $diamond->setDurationMonths(1);

        $manager->persist($diamond);

        $this->addReference('subscription_diamond', $diamond);

        $manager->flush();
    }
}

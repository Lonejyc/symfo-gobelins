<?php
// src/DataFixtures/ItemFixtures.php

namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ItemFixtures extends Fixture
{
    // Définir les raretés de CS:GO pour la clarté
    private const RARITY_CONSUMER_GRADE = 'Consumer Grade'; // Gris
    private const RARITY_INDUSTRIAL_GRADE = 'Industrial Grade'; // Bleu clair
    private const RARITY_MIL_SPEC = 'Mil-Spec';         // Bleu
    private const RARITY_RESTRICTED = 'Restricted';     // Violet
    private const RARITY_CLASSIFIED = 'Classified';     // Rose
    private const RARITY_COVERT = 'Covert';             // Rouge
    private const RARITY_EXTRAORDINARY = 'Extraordinary'; // Or (Knives/Gloves)
    private const RARITY_CONTRABAND = 'Contraband';       // Contrebande (Howl)

    public function load(ObjectManager $manager): void
    {
        $itemsData = [
            // [Name, Rarity, Price, ImageSlug]

            // Grades Consommateur (Gris)
            ['P250 | Sand Dune', self::RARITY_CONSUMER_GRADE, 0.05, 'P250+Sand+Dune'],
            ['G3SG1 | Polar Camo', self::RARITY_CONSUMER_GRADE, 0.10, 'G3SG1+Polar+Camo'],

            // Grades Industriels (Bleu clair)
            ['MP7 | Orange Peel', self::RARITY_INDUSTRIAL_GRADE, 0.15, 'MP7+Orange+Peel'],
            ['Five-SeveN | Forest Night', self::RARITY_INDUSTRIAL_GRADE, 0.20, 'Five-SeveN+Forest+Night'],

            // Mil-Spec (Bleu foncé)
            ['P250 | Splash', self::RARITY_MIL_SPEC, 0.50, 'P250+Splash'],

            // Restricted (Violet)
            ['M4A1-S | Cyrex', self::RARITY_RESTRICTED, 8.20, 'M4A1-S+Cyrex'],
            ['Glock-18 | Fade', self::RARITY_RESTRICTED, 5.00, 'Glock-18+Fade'],
            ['P90 | Asiimov', self::RARITY_RESTRICTED, 10.00, 'P90+Asiimov'],

            // Classified (Rose)
            ['AK-47 | Redline', self::RARITY_CLASSIFIED, 15.50, 'AK-47+Redline'],
            ['USP-S | Orion', self::RARITY_CLASSIFIED, 40.00, 'USP-S+Orion'],
            ['Glock-18 | Water Elemental', self::RARITY_CLASSIFIED, 7.50, 'Glock-18+Water'],

            // Covert (Rouge)
            ['AWP | Dragon Lore', self::RARITY_COVERT, 11000.00, 'AWP+Dragon+Lore'],
            ['AWP | Asiimov', self::RARITY_COVERT, 75.00, 'AWP+Asiimov'],
            ['AK-47 | Asiimov', self::RARITY_COVERT, 45.00, 'AK-47+Asiimov'],

            // Extraordinary (Or)
            ['★ Karambit | Fade', self::RARITY_EXTRAORDINARY, 1200.00, 'Karambit+Fade'],

            // Contraband (Howl)
            ['M4A4 | Howl', self::RARITY_CONTRABAND, 3000.00, 'M4A4+Howl'],
        ];

        foreach ($itemsData as [$name, $rarity, $price, $slug]) {
            $item = new Item();
            $item->setName($name);
            $item->setRarity($rarity);
            $item->setBasePrice($price);

            $item->setImageUrl(sprintf('https://placehold.co/256x192/333/FFF?text=%s', $slug));

            $manager->persist($item);
        }

        $manager->flush();
    }
}

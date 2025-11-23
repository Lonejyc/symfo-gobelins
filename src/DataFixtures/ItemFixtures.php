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
            ['P250 | Sand Dune', self::RARITY_CONSUMER_GRADE, 0.05, 'https://csgo-skins.fr/img/skins/p250-dune-de-sable-big.png'],
            ['G3SG1 | Polar Camo', self::RARITY_CONSUMER_GRADE, 0.10, 'https://csgo-skins.fr/img/skins/g3sg1-camouflage-polaire-big.png'],

            // Grades Industriels (Bleu clair)
            ['MP7 | Orange Peel', self::RARITY_INDUSTRIAL_GRADE, 0.15, 'https://csgo-skins.fr/img/skins/mp7-peau-d-orange-big.png'],
            ['Five-SeveN | Forest Night', self::RARITY_INDUSTRIAL_GRADE, 0.20, 'https://csgo-skins.fr/img/skins/five-seven-nuit-forestiere-big.png'],

            // Mil-Spec (Bleu foncé)
            ['P250 | Splash', self::RARITY_MIL_SPEC, 0.50, 'https://csgo-skins.fr/img/skins/p250-splash-big.png'],

            // Restricted (Violet)
            ['M4A1-S | Cyrex', self::RARITY_RESTRICTED, 8.20, 'https://csgo-skins.fr/img/skins/m4a1-s-cyrex-big.png'],
            ['Glock-18 | Fade', self::RARITY_RESTRICTED, 5.00, 'https://csgo-skins.fr/img/skins/glock-18-degrade-big.png'],
            ['P90 | Asiimov', self::RARITY_RESTRICTED, 10.00, 'https://csgo-skins.fr/img/skins/p90-asiimov-big.png'],

            // Classified (Rose)
            ['AK-47 | Redline', self::RARITY_CLASSIFIED, 15.50, 'https://csgo-skins.fr/img/skins/ak-47-ligne-rouge-big.png'],
            ['USP-S | Orion', self::RARITY_CLASSIFIED, 40.00, 'https://csgo-skins.fr/img/skins/usp-s-orion-big.png'],
            ['Glock-18 | Water Elemental', self::RARITY_CLASSIFIED, 7.50, 'https://csgo-skins.fr/img/skins/glock-18-Elementaire-d-eau-big.png'],

            // Covert (Rouge)
            ['AWP | Dragon Lore', self::RARITY_COVERT, 11000.00, 'https://csgo-skins.fr/img/skins/awp-traditions-des-dragons-big.png'],
            ['AWP | Asiimov', self::RARITY_COVERT, 75.00, 'https://csgo-skins.fr/img/skins/awp-asiimov-big.png'],
            ['AK-47 | Asiimov', self::RARITY_COVERT, 45.00, 'https://csgo-skins.fr/img/skins/ak-47-asiimov-big.png'],

            // Extraordinary (Or)
            ['★ Karambit | Fade', self::RARITY_EXTRAORDINARY, 1200.00, 'https://csgo-skins.fr/img/skins/karambit-degrade-big.png'],

            // Contraband (Howl)
            ['M4A4 | Howl', self::RARITY_CONTRABAND, 3000.00, 'https://csgo-skins.fr/img/skins/m4a4-%E9%BE%8D%E7%8E%8B-(roi-dragon)-big.png'],
        ];

        foreach ($itemsData as [$name, $rarity, $price, $slug]) {
            $item = new Item();
            $item->setName($name);
            $item->setRarity($rarity);
            $item->setBasePrice($price);

            $item->setImageUrl($slug);

            $manager->persist($item);
        }

        $manager->flush();
    }
}

<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class ContractRequest
{
    #[Assert\Count(
        min: 10,
        max: 10,
        exactMessage: "Vous devez sélectionner 10 items pour un contrat."
    )]
    #[Assert\All([
        new Assert\Type('integer')
    ])]
    public array $inventoryItemIds = [];

    #[Assert\Choice(choices: [1.2, 5.0, 10.0], message: "Le facteur de risque doit être 1.2, 5 ou 10.")]
    public float $riskFactor = 1.2;
}

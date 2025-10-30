<?php
// src/Enum/UserTier.php

namespace App\Enum;

enum UserTier: string
{
    case BASIC = 'basic';
    case GOLD = 'gold';
    case DIAMOND = 'diamond';
}

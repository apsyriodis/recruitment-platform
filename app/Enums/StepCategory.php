<?php

namespace App\Enums;

use App\Traits\EnumTrait;

enum StepCategory: string
{
    use EnumTrait;

    case FIRST_INTERVIEW = 'First Interview';
    case TECH_ASSESSMENT = 'Tech Assessment';
    case OFFER = 'Offer';

    public function label(): string
    {
        return match ($this) {
            self::FIRST_INTERVIEW => 'Πρώτη Συνέντευξη',
            self::TECH_ASSESSMENT => 'Τεχνική Αξιολόγηση',
            self::OFFER => 'Πρόταση Εργασίας',
        };
    }
}

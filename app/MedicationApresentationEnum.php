<?php

namespace App;

enum MedicationApresentationEnum
{
    case CAPSULE;
    case TABLET;
    case SOLVENT;
    public function label()
    {
        return match($this)
        {
            self::CAPSULE => __('Capsule'),
            self::TABLET => __('Tablet'),
            self::SOLVENT => __('Solvent')
        };
    }
    public static function values(): array
    {
        return array_map( fn($case) => $case->name, static::cases());
    }
}

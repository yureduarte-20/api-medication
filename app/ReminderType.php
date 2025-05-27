<?php

namespace App;

/**
 * @OA\Schema(
 *     schema="ReminderType",
 *     type="string",
 *     enum={"SCHEDULED", "SINGLE"},
 *     example="SCHEDULED",
 *     description="Tipo de lembrete: AGENDADO (recorrente) ou ÚNICO (data específica)"
 * )
 */
enum ReminderType
{
    case SCHEDULED;
    case SINGLE;
    public function label()
    {
        return match ($this){
            self::SCHEDULED => ucfirst(__('SCHEDULED')),
            self::SINGLE => ucfirst(__('SINGLE')),
        };
    }
    public static function values(): array
    {
        return array_map( fn($case) => $case->name, static::cases());
    }
}

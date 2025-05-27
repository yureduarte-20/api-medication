<?php

namespace App;

enum WeekDay
{
    case MON;
    case TUE;
    case WED;
    case THU;
    case FRI;
    case SAT;
    case SUN;
    public function label()
    {
        return match ($this) {
            self::MON => 'SEG',
            self::TUE => 'TER',
            self::WED => 'QUA',
            self::THU => 'QUI',
            self::FRI => 'SEX',
            self::SAT => 'SAB',
            self::SUN => 'DOM',
        };
    }

    /**
     * Retorna o nome completo do dia da semana (potencialmente traduzido).
     *
     * @return string
     */
    public function fullName(): string
    {
        return match ($this) {
            self::MON => __('Monday'),
            self::TUE => __('Tuesday'),
            self::WED => __('Wednesday'),
            self::THU => __('Thursday'),
            self::FRI => __('Friday'),
            self::SAT => __('Saturday'),
            self::SUN => __('Sunday'),
        };
    }
    public static function values(): array
    {
        return array_map( fn($case) => $case->name, static::cases());
    }

}

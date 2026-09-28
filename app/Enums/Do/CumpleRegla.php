<?php

namespace App\Enums\Do;

enum CumpleRegla: string
{
    case Si = 'si';
    case No = 'no';
    case NoAplica = 'na';

    public function label(): string
    {
        return match ($this) {
            self::Si => 'Sí',
            self::No => 'No',
            self::NoAplica => 'N.A.',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Si => 'success',
            self::No => 'danger',
            self::NoAplica => 'gray',
        };
    }
}

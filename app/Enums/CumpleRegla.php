<?php

namespace App\Enums;

enum CumpleRegla: string
{
    case Si = 'si';
    case No = 'no';
    case Na = 'na';

    public function label(): string
    {
        return match ($this) {
            self::Si => 'Sí',
            self::No => 'No',
            self::Na => 'N/A',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Si => 'success',
            self::No => 'danger',
            self::Na => 'gray',
        };
    }
}

<?php

namespace App\Observers;

use App\Models\Compromiso;

class CompromisoObserver
{
    public function creating(Compromiso $compromiso): void
    {
        if (empty($compromiso->created_by)) {
            $compromiso->created_by = auth()->id();
        }
    }
}

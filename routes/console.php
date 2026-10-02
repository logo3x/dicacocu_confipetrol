<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Revisa a primera hora los plazos del ciclo DICACOCU y avisa a los
// responsables de lo que esta por vencerse o ya vencio.
Schedule::command('do:avisar-plazos')
    ->dailyAt('07:00')
    ->weekdays()
    ->withoutOverlapping();

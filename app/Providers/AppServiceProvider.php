<?php

namespace App\Providers;

use App\Models\Documento;
use App\Observers\DocumentoObserver;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Documento::observe(DocumentoObserver::class);
    }
}

<?php

namespace App\Providers;

use App\Models\Documento;
use App\Observers\DocumentoObserver;
use App\Policies\DocumentoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Documento::class => DocumentoPolicy::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $this->registerPolicies();

        Documento::observe(DocumentoObserver::class);

        // Superadmin omite todas las políticas
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });
    }
}

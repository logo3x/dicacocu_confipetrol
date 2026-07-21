<?php

namespace App\Providers;

use App\Models\AcompanamientoVerificacion;
use App\Models\Actividad;
use App\Models\Compromiso;
use App\Models\Documento;
use App\Models\InspeccionGerencialAccion;
use App\Models\InspeccionGerencialRegla;
use App\Observers\AcompanamientoVerificacionObserver;
use App\Observers\ActividadObserver;
use App\Observers\CompromisoObserver;
use App\Observers\DocumentoObserver;
use App\Observers\InspeccionGerencialAccionObserver;
use App\Observers\InspeccionGerencialReglaObserver;
use App\Policies\AcompanamientoVerificacionPolicy;
use App\Policies\ActividadPolicy;
use App\Policies\CompromisoPolicy;
use App\Policies\DocumentoPolicy;
use App\Policies\InspeccionGerencialAccionPolicy;
use App\Policies\InspeccionGerencialReglaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends AuthServiceProvider
{
    protected $policies = [
        Documento::class => DocumentoPolicy::class,
        Actividad::class => ActividadPolicy::class,
        AcompanamientoVerificacion::class => AcompanamientoVerificacionPolicy::class,
        Compromiso::class => CompromisoPolicy::class,
        InspeccionGerencialRegla::class => InspeccionGerencialReglaPolicy::class,
        InspeccionGerencialAccion::class => InspeccionGerencialAccionPolicy::class,
    ];

    public function register(): void {}

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        $this->registerPolicies();

        Documento::observe(DocumentoObserver::class);
        Actividad::observe(ActividadObserver::class);
        AcompanamientoVerificacion::observe(AcompanamientoVerificacionObserver::class);
        Compromiso::observe(CompromisoObserver::class);
        InspeccionGerencialRegla::observe(InspeccionGerencialReglaObserver::class);
        InspeccionGerencialAccion::observe(InspeccionGerencialAccionObserver::class);

        // Superadmin omite todas las políticas
        Gate::before(function ($user, $ability) {
            if ($user->hasRole('super_admin')) {
                return true;
            }
        });
    }
}

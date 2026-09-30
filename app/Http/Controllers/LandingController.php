<?php

namespace App\Http\Controllers;

use App\Models\Do\Contrato;
use App\Models\Do\EvaluacionF14;
use App\Models\Do\ProcedimientoDo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

class LandingController extends Controller
{
    public function home(): View
    {
        return view('landing.home', ['cifras' => $this->cifras()]);
    }

    public function sgd(): RedirectResponse
    {
        return redirect()->route('landing', ['#sistema']);
    }

    public function dicacocu(): RedirectResponse
    {
        return redirect()->route('landing', ['#dicacocu']);
    }

    public function contacto(): RedirectResponse
    {
        return redirect()->route('landing', ['#contacto']);
    }

    /**
     * Cifras reales del sistema para la página pública.
     *
     * @return array{procedimientos: int, estandarizados: int, contratos: int, verificaciones: int}
     */
    private function cifras(): array
    {
        $vacias = [
            'procedimientos' => 0,
            'estandarizados' => 0,
            'contratos' => 0,
            'verificaciones' => 0,
        ];

        try {
            return Cache::remember('landing_cifras', now()->addMinutes(15), fn (): array => [
                'procedimientos' => ProcedimientoDo::count(),
                'estandarizados' => ProcedimientoDo::whereNotNull('codigo_asignado')->count(),
                'contratos' => Contrato::where('activo', true)->count(),
                'verificaciones' => EvaluacionF14::count(),
            ]);
        } catch (Throwable $e) {
            // La página pública no debe caerse si la base de datos no responde.
            Log::warning('No se pudieron leer las cifras de la página pública', ['error' => $e->getMessage()]);

            return $vacias;
        }
    }
}

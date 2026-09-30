<?php

namespace App\Models;

use App\Models\Do\Campo;
use App\Models\Do\Contrato;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'contrato_id',
        'campo_id',
        'email',
        'password',
        'is_active',
        'last_login_at',
        'avatar_url',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function carpetasCreadas(): HasMany
    {
        return $this->hasMany(Carpeta::class, 'created_by');
    }

    public function documentosCreados(): HasMany
    {
        return $this->hasMany(Documento::class, 'created_by');
    }

    public function documentosResponsable(): HasMany
    {
        return $this->hasMany(Documento::class, 'responsable_id');
    }

    public function documentosAprobador(): HasMany
    {
        return $this->hasMany(Documento::class, 'aprobador_id');
    }

    public function notificaciones(): HasMany
    {
        return $this->hasMany(NotificacionSgd::class);
    }

    public function notificacionesNoLeidas(): HasMany
    {
        return $this->hasMany(NotificacionSgd::class)->whereNull('leido_at');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active && $this->hasPermissionTo('acceder panel admin');
    }

    /** @return BelongsTo<Contrato, $this> */
    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class, 'contrato_id');
    }

    /** @return BelongsTo<Campo, $this> */
    public function campo(): BelongsTo
    {
        return $this->belongsTo(Campo::class, 'campo_id');
    }

    /**
     * Los administradores supervisan todo el ciclo; el resto solo trabaja sobre
     * los procedimientos del contrato al que pertenece.
     */
    public function veTodosLosContratos(): bool
    {
        return $this->hasAnyRole(['super_admin', 'admin']);
    }
}

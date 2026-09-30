<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\RolSistema;
use App\Models\Do\Campo;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Spatie\Permission\Models\Role;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos Personales')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre completo')
                            ->required()
                            ->maxLength(191)
                            ->columnSpanFull(),

                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),

                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->dehydrateStateUsing(fn ($state) => filled($state) ? bcrypt($state) : null)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->placeholder('Dejar en blanco para no cambiar'),
                    ]),

                Section::make('Asignación')
                    ->description('El contrato determina qué procedimientos puede ver y editar el usuario.')
                    ->columns(2)
                    ->schema([
                        Select::make('contrato_id')
                            ->label('Contrato')
                            ->relationship('contrato', 'nombre', fn ($query) => $query->activos())
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(fn (Set $set) => $set('campo_id', null)),

                        Select::make('campo_id')
                            ->label('Campo')
                            ->options(fn (Get $get) => $get('contrato_id')
                                ? Campo::query()
                                    ->activos()
                                    ->where('contrato_id', $get('contrato_id'))
                                    ->orderBy('nombre')
                                    ->pluck('nombre', 'id')
                                    ->all()
                                : [])
                            ->searchable()
                            ->disabled(fn (Get $get): bool => blank($get('contrato_id')))
                            ->helperText('Seleccione primero el contrato'),
                    ]),

                Section::make('Acceso y Rol')
                    ->columns(2)
                    ->schema([
                        Select::make('roles')
                            ->label('Rol')
                            ->relationship('roles', 'name')
                            ->getOptionLabelFromRecordUsing(fn (Role $rol): string => RolSistema::etiqueta($rol->name))
                            ->multiple()
                            ->preload()
                            ->searchable(),

                        Toggle::make('is_active')
                            ->label('Usuario activo')
                            ->default(true),
                    ]),
            ]);
    }
}

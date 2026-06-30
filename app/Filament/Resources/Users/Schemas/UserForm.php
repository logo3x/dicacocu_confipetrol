<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
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

                Section::make('Información Laboral')
                    ->columns(3)
                    ->schema([
                        TextInput::make('cargo')
                            ->label('Cargo')
                            ->maxLength(191),

                        TextInput::make('area')
                            ->label('Área')
                            ->maxLength(191),

                        Select::make('sede')
                            ->label('Sede')
                            ->options([
                                'Bogotá'        => 'Bogotá, Colombia',
                                'Lima'          => 'Lima, Perú',
                                'Santiago'      => 'Santiago, Chile',
                                'Cochabamba'    => 'Cochabamba, Bolivia',
                                'Quito'         => 'Quito, Ecuador',
                                'Caracas'       => 'Caracas, Venezuela',
                            ])
                            ->searchable(),
                    ]),

                Section::make('Acceso y Rol')
                    ->columns(2)
                    ->schema([
                        Select::make('roles')
                            ->label('Rol')
                            ->relationship('roles', 'name')
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

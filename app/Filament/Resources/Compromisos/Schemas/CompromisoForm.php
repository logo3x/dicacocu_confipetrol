<?php

namespace App\Filament\Resources\Compromisos\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CompromisoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Compromiso')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre del compromiso')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),

                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->columnSpanFull(),

                        TextInput::make('contrato')
                            ->label('Contrato')
                            ->helperText('Dejar vacío si es un compromiso corporativo/global.')
                            ->maxLength(255),

                        DatePicker::make('fecha_limite')
                            ->label('Fecha límite')
                            ->required()
                            ->default(now()->addMonth()),

                        Select::make('responsable_id')
                            ->label('Responsable')
                            ->relationship('responsable', 'name')
                            ->searchable()
                            ->preload(),

                        Select::make('rol_responsable')
                            ->label('Rol responsable esperado')
                            ->options([
                                'calidad_corporativa' => 'Calidad Corporativa',
                                'lider_om' => 'Líder O&M',
                                'responsable_hseq' => 'Responsable HSEQ',
                                'personal_tecnico' => 'Personal técnico',
                            ]),
                    ]),
            ]);
    }
}

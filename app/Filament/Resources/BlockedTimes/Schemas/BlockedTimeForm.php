<?php

namespace App\Filament\Resources\BlockedTimes\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class BlockedTimeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('barber_id')
                    ->label('Barbero (Dejar en blanco para bloqueo global)')
                    ->relationship('barber', 'name')
                    ->searchable()
                    ->preload(),
                DatePicker::make('date')
                    ->label('Fecha del Bloqueo')
                    ->required(),
                TimePicker::make('start_time')
                    ->label('Hora de Inicio (Opcional)')
                    ->seconds(false),
                TimePicker::make('end_time')
                    ->label('Hora de Fin (Opcional)')
                    ->seconds(false)
                    ->helperText('Si no especificas horas, se bloqueará todo el día.'),
                TextInput::make('reason')
                    ->label('Motivo (Ej. Vacaciones, Mantenimiento)')
                    ->maxLength(255)
                    ->columnSpanFull(),
            ]);
    }
}

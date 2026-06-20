<?php

namespace App\Filament\Resources\BarberResource\RelationManagers;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;

class WorkingHoursRelationManager extends RelationManager
{
    protected static string $relationship = 'workingHours';
    protected static ?string $recordTitleAttribute = 'day_of_week';
    protected static ?string $title = 'Horarios de Trabajo';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('day_of_week')
                    ->label('Día de la semana')
                    ->options([
                        1 => 'Lunes',
                        2 => 'Martes',
                        3 => 'Miércoles',
                        4 => 'Jueves',
                        5 => 'Viernes',
                        6 => 'Sábado',
                        0 => 'Domingo',
                    ])
                    ->required()
                    ->columnSpanFull(),
                TimePicker::make('start_time')
                    ->label('Hora de inicio')
                    ->seconds(false)
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Hora de fin')
                    ->seconds(false)
                    ->required(),
                Toggle::make('active')
                    ->label('Día Laborable')
                    ->default(true)
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('day_of_week')
                    ->label('Día')
                    ->formatStateUsing(fn ($state) => match ((int) $state) {
                        0 => 'Domingo',
                        1 => 'Lunes',
                        2 => 'Martes',
                        3 => 'Miércoles',
                        4 => 'Jueves',
                        5 => 'Viernes',
                        6 => 'Sábado',
                        default => 'Desconocido',
                    })
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Inicio')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Fin')
                    ->time('H:i')
                    ->sortable(),
                IconColumn::make('active')
                    ->label('Laborable')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                \Filament\Actions\CreateAction::make(),
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}

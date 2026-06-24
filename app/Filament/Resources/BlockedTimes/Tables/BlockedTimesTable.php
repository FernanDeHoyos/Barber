<?php

namespace App\Filament\Resources\BlockedTimes\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BlockedTimesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('barber.name')
                    ->label('Barbero')
                    ->default('Global (Todos)')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state === 'Global (Todos)' ? 'danger' : 'primary'),
                TextColumn::make('date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Desde')
                    ->time('H:i')
                    ->default('Todo el día')
                    ->sortable(),
                TextColumn::make('end_time')
                    ->label('Hasta')
                    ->time('H:i')
                    ->default('Todo el día')
                    ->sortable(),
                TextColumn::make('reason')
                    ->label('Motivo')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

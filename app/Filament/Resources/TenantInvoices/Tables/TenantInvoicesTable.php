<?php

namespace App\Filament\Resources\TenantInvoices\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TenantInvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('reference')
                    ->label('Concepto')
                    ->searchable(),
                TextColumn::make('amount')
                    ->label('Monto')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                        default => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'paid' => 'Pagado',
                        'pending' => 'Pendiente',
                        'overdue' => 'Vencido',
                        'cancelled' => 'Cancelado',
                        default => $state,
                    }),
                TextColumn::make('due_date')
                    ->label('Vencimiento')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('paid_at')
                    ->label('Pagado el')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('payment_method')
                    ->label('Método')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}

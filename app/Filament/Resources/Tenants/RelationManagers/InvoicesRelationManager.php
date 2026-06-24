<?php

namespace App\Filament\Resources\Tenants\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class InvoicesRelationManager extends RelationManager
{
    protected static string $relationship = 'invoices';

    protected static ?string $title = 'Historial de Facturación';

    protected static ?string $modelLabel = 'Cobro / Factura';

    protected static ?string $pluralModelLabel = 'Facturas';

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('reference')
                    ->label('Concepto / Referencia')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Ej. Mensualidad Julio 2026'),
                TextInput::make('amount')
                    ->label('Monto')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'paid' => 'Pagado',
                        'overdue' => 'Vencido',
                        'cancelled' => 'Cancelado',
                    ])
                    ->required()
                    ->default('pending'),
                DatePicker::make('due_date')
                    ->label('Fecha de Vencimiento')
                    ->required(),
                DateTimePicker::make('paid_at')
                    ->label('Fecha de Pago')
                    ->helperText('Dejar en blanco si no se ha pagado.'),
                Select::make('payment_method')
                    ->label('Método de Pago')
                    ->options([
                        'manual' => 'Manual (Efectivo/Transferencia)',
                        'stripe' => 'Stripe',
                        'mercadopago' => 'MercadoPago',
                    ])
                    ->default('manual'),
                TextInput::make('transaction_id')
                    ->label('ID de Transacción')
                    ->helperText('Opcional. ID de la pasarela de pagos.')
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('reference')
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
                TextColumn::make('transaction_id')
                    ->label('Transacción')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

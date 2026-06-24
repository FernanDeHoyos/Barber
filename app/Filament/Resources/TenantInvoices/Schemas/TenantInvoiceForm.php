<?php

namespace App\Filament\Resources\TenantInvoices\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TenantInvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('tenant_id')
                    ->required()
                    ->numeric(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                DatePicker::make('due_date')
                    ->required(),
                DateTimePicker::make('paid_at'),
                TextInput::make('reference')
                    ->default(null),
                TextInput::make('transaction_id')
                    ->default(null),
                TextInput::make('payment_method')
                    ->default(null),
            ]);
    }
}

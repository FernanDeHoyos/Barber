<?php

namespace App\Filament\Resources\Customers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CustomerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Hidden::make('tenant_id')
                    ->default(fn () => app()->bound('tenant') && app('tenant') ? app('tenant')->id : auth()->user()?->tenant_id),

                TextInput::make('name')
                    ->label('Nombre')
                    ->required(),
                TextInput::make('phone')
                    ->label('Teléfono')
                    ->tel()
                    ->required(),
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->default(null),
                Textarea::make('notes')
                    ->label('Notas / Preferencias')
                    ->default(null)
                    ->columnSpanFull(),
            ]);
    }
}

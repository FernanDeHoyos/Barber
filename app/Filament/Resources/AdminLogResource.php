<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AdminLogResource\Pages;
use App\Models\AdminLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use UnitEnum;

class AdminLogResource extends Resource
{
    protected static ?string $model = AdminLog::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';
    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('user.name')->label('Usuario')->sortable(),
            TextColumn::make('event')->label('Evento')->sortable()->searchable(),
            TextColumn::make('target_type')->label('Tipo')->sortable(),
            TextColumn::make('description')->label('Descripción')->limit(80),
            TextColumn::make('ip_address')->label('IP'),
            TextColumn::make('created_at')->label('Fecha')->dateTime('d/m/Y H:i'),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAdminLogs::route('/'),
        ];
    }
}

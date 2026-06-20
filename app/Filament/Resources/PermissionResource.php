<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Permission;
use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use UnitEnum;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-key';
    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Clave')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            TextInput::make('title')
                ->label('Título')
                ->required()
                ->maxLength(255),

            Textarea::make('description')
                ->label('Descripción')
                ->rows(4),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label('Clave')->sortable()->searchable(),
            TextColumn::make('title')->label('Título')->sortable()->searchable(),
            TextColumn::make('description')->limit(50)->label('Descripción'),
            TextColumn::make('created_at')->label('Creado')->dateTime('d/m/Y H:i'),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->user()?->isSuperAdmin() ?? false;
    }
}

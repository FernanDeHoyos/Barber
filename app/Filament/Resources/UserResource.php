<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\MultiSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use UnitEnum;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = 'Administración';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('tenant_id')
                ->label('Tenant')
                ->relationship('tenant', 'name')
                ->searchable()
                ->preload(),

            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            TextInput::make('password')
                ->password()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn ($livewireContext) => $livewireContext instanceof Pages\CreateUser)
                ->minLength(8),

            Select::make('role')
                ->options([
                    'superadmin' => 'Super Administrador',
                    'admin' => 'Administrador',
                    'barber' => 'Barbero',
                    'customer' => 'Cliente',
                ])
                ->required(),

            MultiSelect::make('roles')
                ->relationship('roles', 'title')
                ->label('Roles adicionales'),

            MultiSelect::make('permissions')
                ->relationship('permissions', 'title')
                ->label('Permisos directos'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->sortable(),
            TextColumn::make('email')->searchable()->sortable(),
            TextColumn::make('role')->label('Perfil')->sortable(),
            TextColumn::make('tenant.name')->label('Tenant')->sortable()->toggleable(),
            TextColumn::make('roles')
                ->label('Roles')
                ->formatStateUsing(fn ($record) => $record->roles->pluck('title')->join(', ')),
            TextColumn::make('created_at')->label('Creado')->dateTime('d/m/Y H:i'),
        ])->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

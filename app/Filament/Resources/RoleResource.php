<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

class TenantResource extends Resource
{
    protected static ?string $model = Tenant::class;

    protected static BackedEnum|string|null $navigationIcon = Heroicon::BuildingStorefront;

    protected static ?string $navigationLabel = 'Barberías';
    protected static ?string $modelLabel = 'Barbería';
    protected static ?string $pluralModelLabel = 'Barberías';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            Forms\Components\Section::make('Información general')
                ->columns(2)
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),

                    Forms\Components\TextInput::make('slug')
                        ->label('Slug (subdominio)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('URL: http://slug.localhost')
                        ->alphaDash(),

                    Forms\Components\TextInput::make('contact_email')
                        ->label('Email de contacto')
                        ->email()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(20),

                    Forms\Components\Textarea::make('address')
                        ->label('Dirección')
                        ->columnSpanFull(),
                ]),

            Forms\Components\Section::make('Apariencia')
                ->columns(3)
                ->schema([

                    Forms\Components\FileUpload::make('logo_path')
                        ->label('Logo')
                        ->image()
                        ->directory('tenants/logos')
                        ->columnSpanFull(),

                    Forms\Components\ColorPicker::make('primary_color')
                        ->label('Color primario')
                        ->default('#0f0f11'),

                    Forms\Components\ColorPicker::make('secondary_color')
                        ->label('Color secundario')
                        ->default('#d4af37'),

                    Forms\Components\ColorPicker::make('accent_color')
                        ->label('Color acento')
                        ->default('#1a1a1f'),
                ]),

            Forms\Components\Section::make('Estado y plan')
                ->columns(2)
                ->schema([

                    Forms\Components\Select::make('plan')
                        ->label('Plan')
                        ->options([
                            'trial' => 'Trial',
                            'basic' => 'Básico',
                            'pro' => 'Pro',
                        ])
                        ->default('trial')
                        ->required(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Activa')
                        ->default(true),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\ImageColumn::make('logo_path')
                    ->label('Logo')
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->label('Subdominio')
                    ->searchable(),

                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Usuarios')
                    ->counts('users'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->date('d/m/Y'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('plan')
                    ->options([
                        'trial' => 'Trial',
                        'basic' => 'Básico',
                        'pro' => 'Pro',
                    ]),
            ])
            ->recordActions([
    EditAction::make(),
    DeleteAction::make(),
]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit' => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
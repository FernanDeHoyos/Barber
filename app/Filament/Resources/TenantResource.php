<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TenantResource\Pages;
use App\Models\Tenant;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use BackedEnum;

use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;


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

            Section::make('Información general')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('slug', Str::slug($state))
                        ),

                    TextInput::make('slug')
                        ->label('Slug (subdominio)')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255)
                        ->helperText('URL: http://slug.localhost')
                        ->alphaDash(),

                    TextInput::make('contact_email')
                        ->label('Email de contacto')
                        ->email()
                        ->maxLength(255),

                    TextInput::make('phone')
                        ->label('Teléfono')
                        ->tel()
                        ->maxLength(20),

                    Textarea::make('address')
                        ->label('Dirección')
                        ->columnSpanFull(),
                ]),

            Section::make('Apariencia')
                ->columns(3)
                ->schema([
                    FileUpload::make('logo_path')
                        ->label('Logo')
                        ->image()
                        ->directory('tenants/logos')
                        ->columnSpanFull(),

                    ColorPicker::make('primary_color')
                        ->label('Color primario')
                        ->default('#0f0f11'),

                    ColorPicker::make('secondary_color')
                        ->label('Color secundario')
                        ->default('#d4af37'),

                    ColorPicker::make('accent_color')
                        ->label('Color acento')
                        ->default('#1a1a1f'),
                ]),

            Section::make('Estado y plan')
                ->columns(2)
                ->schema([
                    Select::make('plan')
                        ->label('Plan')
                        ->options([
                            'trial'  => 'Trial',
                            'basic'  => 'Básico',
                            'pro'    => 'Pro',
                        ])
                        ->default('trial')
                        ->required(),

                    Toggle::make('is_active')
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
                    ->searchable()
                    ->url(fn ($record) => "http://{$record->slug}.localhost")
                    ->openUrlInNewTab()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'trial' => 'gray',
                        'basic' => 'info',
                        'pro'   => 'success',
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean(),

                Tables\Columns\TextColumn::make('users_count')
                    ->label('Usuarios')
                    ->counts('users')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creada')
                    ->date('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('plan')
                    ->options([
                        'trial' => 'Trial',
                        'basic' => 'Básico',
                        'pro'   => 'Pro',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Estado')
                    ->trueLabel('Solo activas')
                    ->falseLabel('Solo inactivas'),
            ])
            ->actions([
                Action::make('visit')
                    ->label('Ver sitio')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record) => "http://{$record->slug}.localhost")
                    ->openUrlInNewTab(),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTenants::route('/'),
            'create' => Pages\CreateTenant::route('/create'),
            'edit'   => Pages\EditTenant::route('/{record}/edit'),
        ];
    }
}
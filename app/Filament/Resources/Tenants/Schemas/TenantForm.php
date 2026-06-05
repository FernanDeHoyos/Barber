<?php

namespace App\Filament\Resources\Tenants\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Forms\Components\TimePicker;

class TenantForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            Section::make('Información general')
                ->columns(2)
                ->schema([

                    TextInput::make('name')
                        ->label('Nombre')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(
                            fn ($state, callable $set) =>
                            $set('slug', Str::slug($state))
                        ),

                    TextInput::make('slug')
                        ->label('Slug')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('contact_email')
                        ->label('Email')
                        ->email(),

                    TextInput::make('phone')
                        ->label('Teléfono'),

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
                        ->default('#0f0f11'),

                    ColorPicker::make('secondary_color')
                        ->default('#d4af37'),

                    ColorPicker::make('accent_color')
                        ->default('#1a1a1f'),
                ]),

            Section::make('Estado')
                ->columns(2)
                ->schema([

                    Select::make('plan')
                        ->options([
                            'trial' => 'Trial',
                            'basic' => 'Básico',
                            'pro' => 'Pro',
                        ])
                        ->required(),

                    Toggle::make('is_active')
                        ->default(true),
                ]),

                Section::make('Horario de atención')
                    ->columns(2)
                    ->schema([
                        TimePicker::make('opening_time')
                            ->label('Hora de apertura')
                            ->required(),

                        TimePicker::make('closing_time')
                            ->label('Hora de cierre')
                            ->required(),
                    ]),
        ]);
    }
}
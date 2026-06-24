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

                    TextInput::make('google_maps_url')
                        ->label('URL o iframe de Google Maps')
                        ->placeholder('https://goo.gl/maps/... o <iframe src="...">')
                        ->columnSpanFull(),
                ]),

            Section::make('Apariencia')
                ->columns(3)
                ->schema([

                    FileUpload::make('logo_path')
                        ->label('Logo')
                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                        ->saveUploadedFileUsing(function (\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file): string {
                            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://freeimage.host/api/1/upload', [
                                'key' => '6d207e02198a847aa98d0a2a901485a5',
                                'action' => 'upload',
                                'source' => base64_encode(file_get_contents($file->getRealPath())),
                                'format' => 'json',
                            ]);
                            return $response->json('image.url') ?? '';
                        })
                        ->fetchFileInformation(false)
                        ->getUploadedFileUsing(function (string $file): ?array {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => null,
                                'url' => str_starts_with($file, 'http') ? $file : \Illuminate\Support\Facades\Storage::url($file),
                            ];
                        })
                        ->columnSpanFull(),

                    ColorPicker::make('primary_color')
                        ->default('#0f0f11'),

                    ColorPicker::make('secondary_color')
                        ->default('#d4af37'),

                        ColorPicker::make('accent_color')
                            ->default('#1a1a1f'),
                ]),

            Section::make('Portada y Bienvenida')
                ->schema([
                    FileUpload::make('hero_image_path')
                        ->label('Foto de Portada')
                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                        ->saveUploadedFileUsing(function (\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file): string {
                            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://freeimage.host/api/1/upload', [
                                'key' => '6d207e02198a847aa98d0a2a901485a5',
                                'action' => 'upload',
                                'source' => base64_encode(file_get_contents($file->getRealPath())),
                                'format' => 'json',
                            ]);
                            return $response->json('image.url') ?? '';
                        })
                        ->fetchFileInformation(false)
                        ->getUploadedFileUsing(function (string $file): ?array {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => null,
                                'url' => str_starts_with($file, 'http') ? $file : \Illuminate\Support\Facades\Storage::url($file),
                            ];
                        })
                        ->columnSpanFull(),
                    TextInput::make('hero_headline')
                        ->label('Título de Bienvenida')
                        ->placeholder('Ej. Cortes clásicos con un toque moderno')
                        ->columnSpanFull(),
                ])->columns(2),

            Section::make('Galería del Local')
                ->schema([
                    FileUpload::make('gallery_paths')
                        ->label('Fotos de la barbería')
                        ->multiple()
                        ->acceptedFileTypes(['image/png', 'image/jpeg', 'image/webp'])
                        ->saveUploadedFileUsing(function (\Livewire\Features\SupportFileUploads\TemporaryUploadedFile $file): string {
                            $response = \Illuminate\Support\Facades\Http::asForm()->post('https://freeimage.host/api/1/upload', [
                                'key' => '6d207e02198a847aa98d0a2a901485a5',
                                'action' => 'upload',
                                'source' => base64_encode(file_get_contents($file->getRealPath())),
                                'format' => 'json',
                            ]);
                            return $response->json('image.url') ?? '';
                        })
                        ->fetchFileInformation(false)
                        ->getUploadedFileUsing(function (string $file): ?array {
                            return [
                                'name' => basename($file),
                                'size' => 0,
                                'type' => null,
                                'url' => str_starts_with($file, 'http') ? $file : \Illuminate\Support\Facades\Storage::url($file),
                            ];
                        })
                        ->columnSpanFull(),
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
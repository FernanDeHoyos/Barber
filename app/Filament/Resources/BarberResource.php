<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BarberResource\Pages;
use App\Models\Barber;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;

class BarberResource extends Resource
{
    protected static ?string $model = Barber::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Barberos';
    protected static ?string $modelLabel = 'Barbero';
    protected static ?string $pluralModelLabel = 'Barberos';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            TextInput::make('name')
                ->label('Nombre Completo')
                ->required()
                ->maxLength(255),
            
            Select::make('user_id')
                ->label('Cuenta de Usuario (Opcional)')
                ->relationship('user', 'email')
                ->searchable()
                ->preload()
                ->helperText('Asocia este perfil a un usuario para que pueda iniciar sesión.'),
            
            FileUpload::make('photo_path')
                ->label('Foto de Perfil')
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

            Textarea::make('bio')
                ->label('Biografía')
                ->columnSpanFull(),

            Toggle::make('is_active')
                ->label('Activo')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('photo_path')
                    ->label('Foto')
                    ->circular(),
                
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email Asociado')
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BarberResource\RelationManagers\ServicesRelationManager::class,
            BarberResource\RelationManagers\WorkingHoursRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarbers::route('/'),
            'create' => Pages\CreateBarber::route('/create'),
            'edit' => Pages\EditBarber::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        if (app()->bound('tenant') && app('tenant')) {
            $query->where('tenant_id', app('tenant')->id);
        }
        return $query;
    }
}

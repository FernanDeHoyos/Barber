<?php

namespace App\Filament\Pages;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\Textarea;
use Filament\Pages\Page;
use Filament\Support\Exceptions\Halt;
use Filament\Notifications\Notification;
use App\Models\Tenant;

class TenantProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-storefront';
    protected static ?string $navigationLabel = 'Perfil de Barbería';
    protected static ?string $title = 'Configuración de la Barbería';
    protected static ?string $slug = 'perfil-barberia';

    protected string $view = 'filament.pages.tenant-profile';

    public ?array $data = [];

    public function mount(): void
    {
        $tenantId = app()->bound('tenant') && app('tenant') ? app('tenant')->id : auth()->user()?->tenant_id;
        $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : \App\Models\Tenant::first();
        
        if ($tenant) {
            $this->form->fill($tenant->toArray());
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Información General')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required(),
                        TextInput::make('contact_email')
                            ->label('Email de Contacto')
                            ->email(),
                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->tel(),
                        Textarea::make('address')
                            ->label('Dirección')
                            ->columnSpanFull(),
                        \Filament\Forms\Components\TimePicker::make('opening_time')
                            ->label('Hora de Apertura')
                            ->seconds(false),
                        \Filament\Forms\Components\TimePicker::make('closing_time')
                            ->label('Hora de Cierre')
                            ->seconds(false),
                    ])->columns(2),
                Section::make('Personalización')
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
                            ->columnSpanFull(),
                        ColorPicker::make('primary_color')
                            ->label('Color Primario'),
                        ColorPicker::make('secondary_color')
                            ->label('Color Secundario'),
                        ColorPicker::make('accent_color')
                            ->label('Color Acento'),
                    ])->columns(3),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        try {
            $data = $this->form->getState();
            $tenantId = app()->bound('tenant') && app('tenant') ? app('tenant')->id : auth()->user()?->tenant_id;
            $tenant = $tenantId ? \App\Models\Tenant::find($tenantId) : \App\Models\Tenant::first();
            
            if ($tenant) {
                $tenant->update($data);
                
                // Self-heal: asignarle el tenant al usuario si estaba huérfano
                if (auth()->user() && !auth()->user()->tenant_id) {
                    auth()->user()->update(['tenant_id' => $tenant->id]);
                }
            }

            Notification::make()
                ->success()
                ->title('Perfil actualizado')
                ->send();
        } catch (Halt $exception) {
            return;
        }
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->role !== 'superadmin';
    }
}

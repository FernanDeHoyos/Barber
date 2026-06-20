<?php

namespace App\Filament\Resources\BarberResource\Pages;

use App\Filament\Resources\BarberResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBarber extends CreateRecord
{
    protected static string $resource = BarberResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenantId = app()->bound('tenant') && app('tenant') ? app('tenant')->id : auth()->user()?->tenant_id;
        if ($tenantId) {
            $data['tenant_id'] = $tenantId;
        }
        return $data;
    }
}

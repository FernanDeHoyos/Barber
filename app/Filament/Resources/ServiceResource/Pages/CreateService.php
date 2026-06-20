<?php

namespace App\Filament\Resources\ServiceResource\Pages;

use App\Filament\Resources\ServiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenantId = app()->bound('tenant') && app('tenant') ? app('tenant')->id : auth()->user()?->tenant_id;
        if ($tenantId) {
            $data['tenant_id'] = $tenantId;
        }
        return $data;
    }
}

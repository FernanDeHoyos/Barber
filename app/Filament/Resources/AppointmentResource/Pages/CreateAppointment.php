<?php

namespace App\Filament\Resources\AppointmentResource\Pages;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAppointment extends CreateRecord
{
    protected static string $resource = AppointmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $tenantId = app()->bound('tenant') && app('tenant') ? app('tenant')->id : auth()->user()?->tenant_id;
        if ($tenantId) {
            $data['tenant_id'] = $tenantId;
        }
        return $data;
    }
}

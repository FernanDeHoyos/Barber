<?php

namespace App\Filament\Resources\BlockedTimes\Pages;

use App\Filament\Resources\BlockedTimes\BlockedTimeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBlockedTime extends EditRecord
{
    protected static string $resource = BlockedTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Resources\BlockedTimes\Pages;

use App\Filament\Resources\BlockedTimes\BlockedTimeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBlockedTimes extends ListRecords
{
    protected static string $resource = BlockedTimeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

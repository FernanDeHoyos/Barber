<?php

namespace App\Filament\Resources\BlockedTimes\Pages;

use App\Filament\Resources\BlockedTimes\BlockedTimeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBlockedTime extends CreateRecord
{
    protected static string $resource = BlockedTimeResource::class;
}

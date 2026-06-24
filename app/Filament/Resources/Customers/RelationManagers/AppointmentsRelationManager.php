<?php

namespace App\Filament\Resources\Customers\RelationManagers;

use App\Filament\Resources\AppointmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class AppointmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'appointments';

    protected static ?string $relatedResource = AppointmentResource::class;

    protected static ?string $title = 'Historial de Citas';

    protected static ?string $modelLabel = 'Cita';

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}

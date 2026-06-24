<?php

namespace App\Filament\Resources\BlockedTimes;

use App\Filament\Resources\BlockedTimes\Pages\CreateBlockedTime;
use App\Filament\Resources\BlockedTimes\Pages\EditBlockedTime;
use App\Filament\Resources\BlockedTimes\Pages\ListBlockedTimes;
use App\Filament\Resources\BlockedTimes\Schemas\BlockedTimeForm;
use App\Filament\Resources\BlockedTimes\Tables\BlockedTimesTable;
use App\Models\BlockedTime;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BlockedTimeResource extends Resource
{
    protected static ?string $model = BlockedTime::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-lock-closed';
    protected static ?string $navigationLabel = 'Bloqueos de Agenda';
    protected static ?string $modelLabel = 'Día/Hora Bloqueada';
    protected static ?string $pluralModelLabel = 'Bloqueos de Agenda';

    protected static ?string $recordTitleAttribute = 'reason';

    public static function form(Schema $schema): Schema
    {
        return BlockedTimeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BlockedTimesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBlockedTimes::route('/'),
            'create' => CreateBlockedTime::route('/create'),
            'edit' => EditBlockedTime::route('/{record}/edit'),
        ];
    }
}

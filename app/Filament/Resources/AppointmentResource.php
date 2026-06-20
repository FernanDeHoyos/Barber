<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AppointmentResource\Pages;
use App\Models\Appointment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Illuminate\Database\Eloquent\Builder;

class AppointmentResource extends Resource
{
    protected static ?string $model = Appointment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationLabel = 'Citas';
    protected static ?string $modelLabel = 'Cita';
    protected static ?string $pluralModelLabel = 'Citas';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Select::make('barber_id')
                    ->label('Barbero')
                    ->relationship('barber', 'name')
                    ->required()
                    ->searchable(),
                Select::make('customer_id')
                    ->label('Cliente')
                    ->relationship('customer', 'name')
                    ->required()
                    ->searchable(),
                Select::make('service_id')
                    ->label('Servicio')
                    ->relationship('service', 'name')
                    ->required()
                    ->searchable(),
                DatePicker::make('appointment_date')
                    ->label('Fecha')
                    ->required(),
                TimePicker::make('start_time')
                    ->label('Hora de inicio')
                    ->seconds(false)
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Hora de fin')
                    ->seconds(false)
                    ->required(),
                Select::make('status')
                    ->label('Estado')
                    ->options([
                        'pending' => 'Pendiente',
                        'confirmed' => 'Confirmada',
                        'completed' => 'Completada',
                        'cancelled' => 'Cancelada',
                    ])
                    ->required()
                    ->default('pending'),
                Textarea::make('notes')
                    ->label('Notas')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('appointment_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),
                TextColumn::make('start_time')
                    ->label('Hora')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('customer.name')
                    ->label('Cliente')
                    ->searchable(),
                TextColumn::make('barber.name')
                    ->label('Barbero')
                    ->searchable(),
                TextColumn::make('service.name')
                    ->label('Servicio'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->colors([
                        'warning' => 'pending',
                        'primary' => 'confirmed',
                        'success' => 'completed',
                        'danger' => 'cancelled',
                    ]),
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
            ])
            ->defaultSort('appointment_date', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAppointments::route('/'),
            'create' => Pages\CreateAppointment::route('/create'),
            'edit' => Pages\EditAppointment::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();
        $tenantId = app()->bound('tenant') && app('tenant') ? app('tenant')->id : auth()->user()?->tenant_id;
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        return $query;
    }
}

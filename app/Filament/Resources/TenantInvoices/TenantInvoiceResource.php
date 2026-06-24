<?php

namespace App\Filament\Resources\TenantInvoices;

use App\Filament\Resources\TenantInvoices\Pages\CreateTenantInvoice;
use App\Filament\Resources\TenantInvoices\Pages\EditTenantInvoice;
use App\Filament\Resources\TenantInvoices\Pages\ListTenantInvoices;
use App\Filament\Resources\TenantInvoices\Pages\ViewTenantInvoice;
use App\Filament\Resources\TenantInvoices\Schemas\TenantInvoiceForm;
use App\Filament\Resources\TenantInvoices\Schemas\TenantInvoiceInfolist;
use App\Filament\Resources\TenantInvoices\Tables\TenantInvoicesTable;
use App\Models\TenantInvoice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TenantInvoiceResource extends Resource
{
    protected static ?string $model = TenantInvoice::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-credit-card';

    protected static ?string $navigationLabel = 'Mi Facturación';
    protected static ?string $modelLabel = 'Factura';
    protected static ?string $pluralModelLabel = 'Facturas';
    protected static ?string $slug = 'billing';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    protected static ?string $recordTitleAttribute = 'reference';

    public static function form(Schema $schema): Schema
    {
        return TenantInvoiceForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return TenantInvoiceInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TenantInvoicesTable::configure($table);
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();

        if ($user && $user->role !== 'superadmin') {
            $query->where('tenant_id', $user->tenant_id);
        }

        return $query;
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
            'index' => ListTenantInvoices::route('/'),
            'create' => CreateTenantInvoice::route('/create'),
            'view' => ViewTenantInvoice::route('/{record}'),
            'edit' => EditTenantInvoice::route('/{record}/edit'),
        ];
    }
}

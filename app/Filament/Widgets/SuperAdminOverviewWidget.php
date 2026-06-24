<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Tenant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;

class SuperAdminOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 0; // Mostrarlo hasta arriba

    public static function canView(): bool
    {
        return auth()->user()->hasRole('superadmin');
    }

    protected function getStats(): array
    {
        $activeTenants = Tenant::where('is_active', true)->count();
        $totalAppointments = Appointment::count();
        
        $totalRevenue = DB::table('appointments')
            ->join('services', 'appointments.service_id', '=', 'services.id')
            ->sum('services.price');

        return [
            Stat::make('🌐 Barberías Activas', $activeTenants)
                ->description('Total en la plataforma')
                ->descriptionIcon('heroicon-m-building-storefront')
                ->color('primary')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('🌐 Citas Históricas', $totalAppointments)
                ->description('Citas agendadas globalmente')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('success')
                ->chart([3, 12, 5, 20, 8, 25, 10]),

            Stat::make('🌐 Volumen de Negocio', '$' . number_format($totalRevenue, 2))
                ->description('Ingresos brutos generados')
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('warning')
                ->chart([1, 4, 2, 8, 5, 12, 7]),
        ];
    }
}

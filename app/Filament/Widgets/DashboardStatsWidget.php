<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Appointment;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected ?string $pollingInterval = '30s';

    protected function getStats(): array
    {
        $tenantId = Filament::getTenant()?->id ?? auth()->user()?->tenant_id;

        if (!$tenantId) {
            return [];
        }

        // 1. Citas de Hoy
        $citasHoy = Appointment::where('tenant_id', $tenantId)
            ->whereDate('appointment_date', Carbon::today())
            ->count();

        // 2. Citas Completadas Hoy (Ingresos Estimados)
        $citasCompletadas = Appointment::where('tenant_id', $tenantId)
            ->where('status', 'completed')
            ->whereDate('appointment_date', Carbon::today())
            ->count();

        // 3. Servicio Más Solicitado Histórico
        $mostRequestedService = Appointment::where('tenant_id', $tenantId)
            ->select('service_id', DB::raw('count(*) as total'))
            ->whereNotNull('service_id')
            ->groupBy('service_id')
            ->orderByDesc('total')
            ->first();
        
        $serviceName = $mostRequestedService && $mostRequestedService->service 
            ? $mostRequestedService->service->name 
            : 'Sin datos';

        // 4. Barbero Más Ocupado (Mes Actual)
        $busiestBarber = Appointment::where('tenant_id', $tenantId)
            ->whereMonth('appointment_date', Carbon::now()->month)
            ->select('barber_id', DB::raw('count(*) as total'))
            ->whereNotNull('barber_id')
            ->groupBy('barber_id')
            ->orderByDesc('total')
            ->first();
            
        $barberName = $busiestBarber && $busiestBarber->barber 
            ? $busiestBarber->barber->name 
            : 'Sin datos';

        return [
            Stat::make('Citas de Hoy', $citasHoy)
                ->description($citasCompletadas . ' citas completadas')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color($citasHoy > 0 ? 'primary' : 'gray'),
            
            Stat::make('Servicio Top', $serviceName)
                ->description('El más solicitado históricamente')
                ->descriptionIcon('heroicon-m-star')
                ->color('success'),
                
            Stat::make('Barbero del Mes', $barberName)
                ->description('Más citas este mes')
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),
        ];
    }
}

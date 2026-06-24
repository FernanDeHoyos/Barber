<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Appointment;
use Filament\Facades\Filament;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class AppointmentsChartWidget extends ChartWidget
{
    protected ?string $heading = 'Flujo de Citas (Últimos 7 días)';
    protected static ?int $sort = 2;
    protected ?string $maxHeight = '300px';

    protected function getData(): array
    {
        $tenantId = Filament::getTenant()?->id ?? auth()->user()?->tenant_id;

        $data = [];
        $labels = [];

        if ($tenantId) {
            $period = CarbonPeriod::create(Carbon::now()->subDays(6), Carbon::now());

            foreach ($period as $date) {
                $count = Appointment::where('tenant_id', $tenantId)
                    ->whereDate('appointment_date', $date->format('Y-m-d'))
                    ->count();

                // 'd M' example: 15 Jun
                $labels[] = $date->translatedFormat('d M');
                $data[] = $count;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Citas Registradas',
                    'data' => $data,
                    'backgroundColor' => '#f59e0b', // Amber to match standard primary
                    'borderColor' => '#f59e0b',
                    'fill' => 'start',
                    'tension' => 0.4, // Smooth curve
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}

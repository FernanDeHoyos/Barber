<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\Appointment;

class Calendar extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    protected static ?string $navigationLabel = 'Calendario';
    protected static ?string $title = 'Calendario de Citas';
    protected string $view = 'filament.pages.calendar';

    protected function getViewData(): array
    {
        $tenantId = app()->bound('tenant') && app('tenant') ? app('tenant')->id : auth()->user()?->tenant_id;
        
        $events = [];
        if ($tenantId) {
            $appointments = Appointment::with(['barber', 'customer', 'service'])
                ->where('tenant_id', $tenantId)
                ->get();

            foreach ($appointments as $appointment) {
                // Determine color based on status
                $color = match ($appointment->status) {
                    'pending' => '#f59e0b', // Amber
                    'confirmed' => '#3b82f6', // Blue
                    'completed' => '#10b981', // Green
                    'cancelled' => '#ef4444', // Red
                    default => '#6b7280', // Gray
                };

                $events[] = [
                    'id' => $appointment->id,
                    'title' => ($appointment->customer->name ?? 'Cliente') . ' (' . ($appointment->service->name ?? 'Servicio') . ')',
                    'start' => $appointment->appointment_date . 'T' . $appointment->start_time,
                    'end' => $appointment->appointment_date . 'T' . $appointment->end_time,
                    'color' => $color,
                ];
            }
        }

        return [
            'events' => $events,
        ];
    }
}

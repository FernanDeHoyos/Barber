<?php

namespace App\Services;

use App\Models\Barber;
use Carbon\Carbon;

class AppointmentAvailabilityService
{
    public static function getAvailableSlots(Barber $barber, string $date, int $durationMinutes, int $intervalMinutes = 10): array
    {
        $targetDate = Carbon::parse($date);
        
        // --- BLOCKED TIMES LOGIC ---
        $blockedTimes = \App\Models\BlockedTime::where('tenant_id', $barber->tenant_id)
            ->whereDate('date', $targetDate)
            ->where(function($q) use ($barber) {
                $q->whereNull('barber_id')->orWhere('barber_id', $barber->id);
            })
            ->get();
            
        // Si hay algún bloqueo de día completo, retornamos vacío
        if ($blockedTimes->contains(fn ($bt) => is_null($bt->start_time) && is_null($bt->end_time))) {
            return [];
        }

        $dayOfWeek = $targetDate->dayOfWeek;

        $workingHour = $barber->workingHours()
            ->where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->first();

        if (! $workingHour) {
            return [];
        }

        $start = Carbon::parse($workingHour->start_time);
        $end = Carbon::parse($workingHour->end_time)->subMinutes($durationMinutes);

        if ($end->lessThan($start)) {
            return [];
        }

        $appointments = $barber->appointments()
            ->whereDate('appointment_date', $targetDate)
            ->get()
            ->map(function ($appointment) {
                return [
                    'start' => Carbon::parse($appointment->start_time),
                    'end' => Carbon::parse($appointment->end_time),
                ];
            });

        // Convertir bloqueos parciales en "citas" para evitar conflictos
        foreach ($blockedTimes as $bt) {
            if ($bt->start_time && $bt->end_time) {
                $appointments->push([
                    'start' => Carbon::parse($bt->start_time),
                    'end' => Carbon::parse($bt->end_time),
                ]);
            }
        }

        $slots = [];

        for ($slot = $start->copy(); $slot->lte($end); $slot->addMinutes($intervalMinutes)) {
            $slotEnd = $slot->copy()->addMinutes($durationMinutes);
            $conflict = $appointments->first(function ($appointment) use ($slot, $slotEnd) {
                return $slot->lt($appointment['end']) && $slotEnd->gt($appointment['start']);
            });

            $slots[] = [
                'time' => $slot->format('H:i'),
                'available' => ! $conflict,
            ];
        }

        return $slots;
    }
}

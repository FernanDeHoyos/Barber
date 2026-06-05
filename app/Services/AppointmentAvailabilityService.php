<?php

namespace App\Services;

use App\Models\Barber;
use Carbon\Carbon;

class AppointmentAvailabilityService
{
    public static function getAvailableSlots(Barber $barber, string $date, int $durationMinutes, int $intervalMinutes = 15): array
    {
        $targetDate = Carbon::parse($date);
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

        $slots = [];

        for ($slot = $start->copy(); $slot->lte($end); $slot->addMinutes($intervalMinutes)) {
            $slotEnd = $slot->copy()->addMinutes($durationMinutes);
            $conflict = $appointments->first(function ($appointment) use ($slot, $slotEnd) {
                return $slot->lt($appointment['end']) && $slotEnd->gt($appointment['start']);
            });

            if (! $conflict) {
                $slots[] = $slot->format('H:i');
            }
        }

        return $slots;
    }
}

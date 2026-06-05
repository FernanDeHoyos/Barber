<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = app('tenant')
            ->appointments()
            ->with(['barber', 'service', 'customer'])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->get();

        return response()->json(['appointments' => $appointments]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'barber_id' => 'required|integer|exists:barbers,id',
            'service_id' => 'required|integer|exists:services,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'appointment_date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ]);

        $tenant = app('tenant');

        $barber = $tenant->barbers()->findOrFail($data['barber_id']);
        $service = $tenant->services()->findOrFail($data['service_id']);

        if (! $barber->services()->where('service_id', $service->id)->exists()) {
            return response()->json(['message' => 'El barbero no ofrece ese servicio.'], 422);
        }

        $start = Carbon::parse($data['start_time']);
        $end = $start->copy()->addMinutes($service->duration_minutes);

        if (! $this->isWithinWorkingHours($barber, $data['appointment_date'], $start, $end)) {
            return response()->json(['message' => 'El horario seleccionado está fuera del horario de atención del barbero.'], 422);
        }

        $conflict = $barber->appointments()
            ->whereDate('appointment_date', $data['appointment_date'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_time', [$start->format('H:i:s'), $end->format('H:i:s')])
                    ->orWhereBetween('end_time', [$start->format('H:i:s'), $end->format('H:i:s')])
                    ->orWhere(function ($query) use ($start, $end) {
                        $query->where('start_time', '<=', $start->format('H:i:s'))
                            ->where('end_time', '>=', $end->format('H:i:s'));
                    });
            })
            ->exists();

        if ($conflict) {
            return response()->json(['message' => 'Ya existe una reserva en ese horario.'], 422);
        }

        $customer = Customer::firstOrCreate([
            'tenant_id' => $tenant->id,
            'phone' => $data['phone'],
        ], [
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
        ]);

        $appointment = Appointment::create([
            'tenant_id' => $tenant->id,
            'barber_id' => $barber->id,
            'service_id' => $service->id,
            'customer_id' => $customer->id,
            'appointment_date' => $data['appointment_date'],
            'start_time' => $start->format('H:i:s'),
            'end_time' => $end->format('H:i:s'),
            'notes' => $data['notes'] ?? null,
        ]);

        return response()->json([
            'appointment' => $appointment->load(['barber', 'service', 'customer']),
        ], 201);
    }

    protected function isWithinWorkingHours($barber, string $date, Carbon $start, Carbon $end): bool
    {
        $targetDate = Carbon::parse($date);
        $dayOfWeek = $targetDate->dayOfWeek;

        $workingHour = $barber->workingHours()
            ->where('day_of_week', $dayOfWeek)
            ->where('active', true)
            ->first();

        if (! $workingHour) {
            return false;
        }

        $shiftStart = Carbon::parse($workingHour->start_time);
        $shiftEnd = Carbon::parse($workingHour->end_time);

        return $start->betweenIncluded($shiftStart, $shiftEnd) && $end->betweenIncluded($shiftStart, $shiftEnd);
    }
}

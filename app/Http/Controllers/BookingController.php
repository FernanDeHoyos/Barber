<?php

namespace App\Http\Controllers;

use App\Services\AppointmentAvailabilityService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function landing()
    {
        return Inertia::render('Tenant/Landing', [
            'tenant' => app('tenant'),
        ]);
    }

    public function index()
    {
        return Inertia::render('Tenant/Booking', [
            'tenant' => app('tenant'),
        ]);
    }

    public function barbers()
    {
        return app('tenant')
            ->barbers()
            ->with(['services' => function ($query) {
                $query->where('is_active', true);
            }, 'workingHours'])
            ->where('is_active', true)
            ->get();
    }

    public function services()
    {
        return app('tenant')
            ->services()
            ->where('is_active', true)
            ->get();
    }

    public function availability(Request $request)
    {
        $data = $request->validate([
            'barber_id' => 'required|integer|exists:barbers,id',
            'service_id' => 'required|integer|exists:services,id',
            'appointment_date' => 'required|date_format:Y-m-d',
        ]);

        $tenant = app('tenant');

        $barber = $tenant->barbers()->findOrFail($data['barber_id']);
        $service = $tenant->services()->findOrFail($data['service_id']);

        if (! $barber->services()->where('service_id', $service->id)->exists()) {
            return response()->json(['message' => 'El barbero no ofrece ese servicio.'], 422);
        }

        return response()->json([
            'available_slots' => AppointmentAvailabilityService::getAvailableSlots(
                $barber,
                $data['appointment_date'],
                $service->duration_minutes,
            ),
        ]);
    }
}

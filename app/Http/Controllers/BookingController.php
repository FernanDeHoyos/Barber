<?php

namespace App\Http\Controllers;

use App\Services\AppointmentAvailabilityService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookingController extends Controller
{
    public function landing()
    {
        $tenant = app('tenant');

        $barbers = $tenant->barbers()
            ->with('services')
            ->where('is_active', true)
            ->get()
            ->map(function ($barber) {
                return [
                    'id' => $barber->id,
                    'name' => $barber->name,
                    'bio' => $barber->bio,
                    'photo_url' => $barber->photo_path ? (str_starts_with($barber->photo_path, 'http') ? $barber->photo_path : \Illuminate\Support\Facades\Storage::url($barber->photo_path)) : null,
                    'services' => $barber->services->pluck('name'),
                ];
            });

        $services = $tenant->services()
            ->where('is_active', true)
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => number_format($service->price, 2),
                    'duration_minutes' => $service->duration_minutes,
                ];
            });

        $tenantData = [
            'id' => $tenant->id,
            'name' => $tenant->name,
            'address' => $tenant->address,
            'logo_url' => $tenant->logo_path ? (str_starts_with($tenant->logo_path, 'http') ? $tenant->logo_path : \Illuminate\Support\Facades\Storage::url($tenant->logo_path)) : null,
            'primary_color' => $tenant->primary_color ?? '#f59e0b',
            'secondary_color' => $tenant->secondary_color ?? '#d4af37',
            'accent_color' => $tenant->accent_color ?? '#1a1a1f',
            'hero_image_url' => $tenant->hero_image_path ? (str_starts_with($tenant->hero_image_path, 'http') ? $tenant->hero_image_path : \Illuminate\Support\Facades\Storage::url($tenant->hero_image_path)) : null,
            'hero_headline' => $tenant->hero_headline,
            'google_maps_url' => $tenant->google_maps_url,
            'gallery_urls' => $tenant->gallery_paths ? array_map(fn($path) => str_starts_with($path, 'http') ? $path : \Illuminate\Support\Facades\Storage::url($path), $tenant->gallery_paths) : [],
        ];

        return Inertia::render('Tenant/Landing', [
            'tenant' => $tenantData,
            'barbers' => $barbers,
            'services' => $services,
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

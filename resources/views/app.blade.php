<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'BarberSaaS') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&display=swap" rel="stylesheet">

        <!-- Scripts & Styles -->
        @vite(['resources/js/app.js'])
        @inertiaHead

        <!-- Dynamic CSS Variables for Tenant Branding -->
        @if(isset($tenant))
        <style id="tenant-branding">
            :root {
                --primary-color: {{ $tenant->primary_color ?? '#0f0f11' }};
                --secondary-color: {{ $tenant->secondary_color ?? '#d4af37' }};
                --accent-color: {{ $tenant->accent_color ?? '#1a1a1f' }};
                --text-light: #f3f4f6;
                --text-dark: #1f2937;
                --text-muted: #9ca3af;
                --font-primary: 'Outfit', sans-serif;
                --font-heading: 'Playfair Display', serif;
            }
        </style>
        @else
        <style id="global-branding">
            :root {
                --primary-color: #0f0f11;
                --secondary-color: #3b82f6;
                --accent-color: #1e1b4b;
                --text-light: #f3f4f6;
                --text-dark: #1f2937;
                --text-muted: #9ca3af;
                --font-primary: 'Outfit', sans-serif;
                --font-heading: 'Playfair Display', serif;
            }
        </style>
        @endif
    </head>
    <body class="antialiased">
        @inertia
    </body>
</html>

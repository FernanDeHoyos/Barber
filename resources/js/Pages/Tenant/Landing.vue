<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  tenant: Object,
  barbers: Array,
  services: Array,
});
</script>

<template>
  <div class="min-h-screen bg-slate-950 text-slate-100 font-sans selection:bg-amber-500/30">
    <!-- Header / Hero Section -->
    <header class="relative px-6 py-24 sm:py-32 lg:px-8 text-center border-b border-slate-800 bg-slate-900/50">
      <div class="mx-auto max-w-2xl">
        <div v-if="tenant.logo_url" class="flex justify-center mb-6">
          <img :src="tenant.logo_url" alt="Logo" class="h-24 w-24 rounded-full object-cover border-2 border-amber-400 shadow-xl" />
        </div>
        <p class="text-sm uppercase tracking-[0.35em] text-amber-400 font-semibold mb-4">Bienvenido a</p>
        <h1 class="text-5xl font-bold tracking-tight text-white sm:text-7xl">{{ tenant.name }}</h1>
        <p class="mt-6 text-lg leading-8 text-slate-300">
          Reserva tu cita online y descubre la experiencia definitiva en cortes y arreglo de barba. 
          Conoce a nuestros barberos y servicios antes de agendar tu turno.
        </p>
        <div class="mt-10 flex items-center justify-center gap-x-6">
          <Link href="/book" class="rounded-full bg-amber-400 px-8 py-4 text-slate-950 font-bold text-lg shadow-lg shadow-amber-500/20 hover:bg-amber-300 hover:scale-105 transition-all">
            Reservar turno ahora
          </Link>
        </div>
      </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 lg:px-8 py-16 space-y-24">
      
      <!-- Services Section -->
      <section v-if="services.length > 0">
        <div class="mb-12 text-center">
          <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">Nuestros Servicios</h2>
          <p class="mt-4 text-slate-400">Calidad y precisión en cada detalle.</p>
        </div>
        
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="service in services" :key="service.id" class="rounded-3xl border border-slate-800 bg-slate-900/40 p-8 hover:bg-slate-800/50 transition-colors">
            <div class="flex justify-between items-start mb-4">
              <h3 class="text-xl font-semibold text-white">{{ service.name }}</h3>
              <span class="inline-flex items-center rounded-full bg-amber-400/10 px-3 py-1 text-sm font-medium text-amber-400 ring-1 ring-inset ring-amber-400/20">
                ${{ service.price }}
              </span>
            </div>
            <p class="text-slate-400 text-sm mb-6">{{ service.description }}</p>
            <div class="flex items-center text-sm text-slate-500">
              <svg class="mr-2 h-5 w-5 text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              {{ service.duration_minutes }} minutos
            </div>
          </div>
        </div>
      </section>

      <!-- Barbers Section -->
      <section v-if="barbers.length > 0">
        <div class="mb-12 text-center">
          <h2 class="text-3xl font-bold tracking-tight sm:text-4xl">El Equipo</h2>
          <p class="mt-4 text-slate-400">Conoce a los profesionales que se encargarán de tu estilo.</p>
        </div>

        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="barber in barbers" :key="barber.id" class="flex flex-col items-center text-center rounded-3xl border border-slate-800 bg-slate-900/40 p-8">
            <img v-if="barber.photo_url" :src="barber.photo_url" :alt="barber.name" class="h-32 w-32 rounded-full object-cover mb-6 border-4 border-slate-800 shadow-xl" />
            <div v-else class="h-32 w-32 rounded-full bg-slate-800 flex items-center justify-center mb-6 border-4 border-slate-700">
              <span class="text-3xl text-slate-500">{{ barber.name.charAt(0) }}</span>
            </div>
            
            <h3 class="text-xl font-semibold text-white">{{ barber.name }}</h3>
            <p class="mt-2 text-sm text-slate-400 italic">"{{ barber.bio }}"</p>
            
            <div class="mt-6 flex flex-wrap justify-center gap-2">
              <span v-for="service in barber.services" :key="service" class="inline-flex items-center rounded-md bg-slate-800 px-2 py-1 text-xs font-medium text-slate-300">
                {{ service }}
              </span>
            </div>
          </div>
        </div>
      </section>

    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-800 py-10 text-center text-sm text-slate-500">
      <p>&copy; {{ new Date().getFullYear() }} {{ tenant.name }}. Todos los derechos reservados.</p>
    </footer>
  </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  tenant: Object,
  barbers: Array,
  services: Array,
});
</script>

<template>
  <div class="min-h-screen bg-zinc-950 text-zinc-300 font-['Outfit'] selection:bg-zinc-800 selection:text-zinc-100">
    <!-- Header / Hero Section -->
    <header class="relative px-6 py-32 sm:py-40 lg:px-8 border-b border-zinc-900 bg-zinc-950"
            :style="tenant.hero_image_url ? `background-image: linear-gradient(rgba(9, 9, 11, 0.75), rgba(9, 9, 11, 0.95)), url('${tenant.hero_image_url}'); background-size: cover; background-position: center;` : ''">
      <div class="mx-auto max-w-3xl relative z-10 flex flex-col items-center text-center">
        <div v-if="tenant.logo_url" class="mb-8">
          <img :src="tenant.logo_url" alt="Logo" class="h-24 w-24 rounded-2xl object-cover border border-zinc-800/50 shadow-sm" />
        </div>
        <p class="text-xs uppercase tracking-[0.3em] font-semibold mb-6 text-zinc-500">Bienvenido a</p>
        <h1 class="text-5xl font-['Playfair_Display'] tracking-tight text-zinc-100 sm:text-7xl">{{ tenant.name }}</h1>
        <p class="mt-8 text-lg leading-relaxed text-zinc-400 font-light max-w-2xl">
          {{ tenant.hero_headline || 'Reserva tu cita online y descubre la experiencia definitiva en cortes y arreglo de barba. Conoce a nuestros barberos y servicios antes de agendar tu turno.' }}
        </p>
        <div class="mt-12">
          <Link href="/book" class="inline-flex items-center justify-center px-8 py-4 text-sm uppercase tracking-wider font-semibold text-zinc-950 rounded-2xl transition-all duration-200 hover:brightness-110" :style="{ backgroundColor: tenant.primary_color || '#f59e0b' }">
            Reservar turno ahora
          </Link>
        </div>
      </div>
    </header>

    <main class="mx-auto max-w-7xl px-6 lg:px-8 py-24 space-y-32">
      
      <!-- Services Section -->
      <section v-if="services.length > 0">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-zinc-900 pb-6">
          <div>
            <h2 class="text-3xl font-['Playfair_Display'] text-zinc-100 sm:text-4xl">Nuestros Servicios</h2>
            <p class="mt-3 text-zinc-500 font-light">Calidad y precisión en cada detalle.</p>
          </div>
        </div>
        
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="service in services" :key="service.id" class="rounded-2xl border border-zinc-800 bg-zinc-900/40 p-8 transition-colors duration-200 hover:bg-zinc-900/80">
            <div class="flex justify-between items-start mb-6">
              <h3 class="text-xl font-medium text-zinc-200">{{ service.name }}</h3>
              <span class="inline-flex items-center rounded-lg px-3 py-1 text-sm font-semibold" :style="{ color: tenant.primary_color || '#f59e0b', backgroundColor: (tenant.primary_color || '#f59e0b') + '1A' }">
                ${{ service.price }}
              </span>
            </div>
            <p class="text-zinc-400 text-sm mb-8 leading-relaxed font-light">{{ service.description }}</p>
            <div class="flex items-center text-xs uppercase tracking-wider font-medium text-zinc-500">
              <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
              </svg>
              {{ service.duration_minutes }} min
            </div>
          </div>
        </div>
      </section>

      <!-- Barbers Section -->
      <section v-if="barbers.length > 0">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-zinc-900 pb-6">
          <div>
            <h2 class="text-3xl font-['Playfair_Display'] text-zinc-100 sm:text-4xl">El Equipo</h2>
            <p class="mt-3 text-zinc-500 font-light">Conoce a los profesionales que definirán tu estilo.</p>
          </div>
        </div>

        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
          <div v-for="barber in barbers" :key="barber.id" class="rounded-2xl border border-zinc-800 bg-zinc-900/40 p-6 flex flex-col md:flex-row gap-6 items-center md:items-start transition-colors duration-200 hover:bg-zinc-900/80">
            <img v-if="barber.photo_url" :src="barber.photo_url" :alt="barber.name" class="h-24 w-24 rounded-2xl object-cover border border-zinc-800 flex-shrink-0" />
            <div v-else class="h-24 w-24 rounded-2xl flex items-center justify-center border border-zinc-800 bg-zinc-950 flex-shrink-0 text-zinc-600">
              <span class="text-3xl font-['Playfair_Display']">{{ barber.name.charAt(0) }}</span>
            </div>
            
            <div class="text-center md:text-left">
              <h3 class="text-lg font-medium text-zinc-200 mb-2">{{ barber.name }}</h3>
              <p class="text-sm text-zinc-500 font-light leading-relaxed mb-4 line-clamp-3">{{ barber.bio }}</p>
              
              <div class="flex flex-wrap justify-center md:justify-start gap-2">
                <span v-for="service in barber.services" :key="service" class="inline-flex items-center rounded-lg border border-zinc-800 bg-zinc-950 px-2.5 py-1 text-xs text-zinc-400">
                  {{ service }}
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- Gallery Section -->
      <section v-if="tenant.gallery_urls && tenant.gallery_urls.length > 0">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-zinc-900 pb-6">
          <div>
            <h2 class="text-3xl font-['Playfair_Display'] text-zinc-100 sm:text-4xl">Galería</h2>
            <p class="mt-3 text-zinc-500 font-light">Instalaciones y trabajos destacados.</p>
          </div>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
          <div v-for="(img, idx) in tenant.gallery_urls" :key="idx" class="aspect-[4/5] rounded-2xl overflow-hidden bg-zinc-900 border border-zinc-800">
            <img :src="img" alt="Galería" class="w-full h-full object-cover grayscale opacity-80 transition-all duration-500 hover:grayscale-0 hover:opacity-100" loading="lazy" />
          </div>
        </div>
      </section>

      <!-- Location Section -->
      <section v-if="tenant.google_maps_url || tenant.address">
        <div class="mb-12 flex flex-col md:flex-row md:items-end justify-between gap-6 border-b border-zinc-900 pb-6">
          <div>
            <h2 class="text-3xl font-['Playfair_Display'] text-zinc-100 sm:text-4xl">Ubicación</h2>
            <p class="mt-3 text-zinc-500 font-light" v-if="tenant.address">{{ tenant.address }}</p>
          </div>
          <div v-if="tenant.google_maps_url">
            <a :href="tenant.google_maps_url" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-sm font-semibold hover:opacity-80 transition-opacity" :style="{ color: tenant.primary_color || '#f59e0b' }">
              Abrir en Mapas <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
          </div>
        </div>
        
        <div class="rounded-2xl border border-zinc-800 bg-zinc-900 overflow-hidden w-full h-[450px]">
          <div v-if="tenant.google_maps_url && tenant.google_maps_url.includes('<iframe')" v-html="tenant.google_maps_url" class="w-full h-full [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0 grayscale-[50%] contrast-[1.1] opacity-90 transition-all duration-500 hover:grayscale-0 hover:opacity-100"></div>
          <div v-else-if="tenant.address" class="w-full h-full grayscale-[50%] contrast-[1.1] opacity-90 transition-all duration-500 hover:grayscale-0 hover:opacity-100">
            <iframe class="w-full h-full" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" :src="`https://maps.google.com/maps?q=${encodeURIComponent(tenant.name + ' ' + tenant.address)}&t=&z=15&ie=UTF8&iwloc=&output=embed`"></iframe>
          </div>
          <div v-else class="w-full h-full flex items-center justify-center text-zinc-600 bg-zinc-950">
            Sin ubicación definida.
          </div>
        </div>
      </section>

    </main>

    <!-- Footer -->
    <footer class="border-t border-zinc-900 bg-zinc-950 py-12 text-center text-sm text-zinc-600 font-light">
      <div v-if="tenant.logo_url" class="flex justify-center mb-6 grayscale opacity-40">
        <img :src="tenant.logo_url" alt="Logo" class="h-10 w-10 rounded-xl object-cover" />
      </div>
      <p>&copy; {{ new Date().getFullYear() }} {{ tenant.name }}. Todos los derechos reservados.</p>
    </footer>
  </div>
</template>
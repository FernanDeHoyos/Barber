<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
  tenant: Object,
});
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-slate-950 text-slate-100 px-4">
    <div class="max-w-3xl text-center space-y-8">
      <div>
        <p class="text-sm uppercase tracking-[0.35em] text-slate-400">Gestor de Turnos SaaS</p>
        <h1 class="mt-4 text-5xl font-bold tracking-tight">{{ tenant.name }}</h1>
        <p class="mt-4 text-lg text-slate-300">Reserva tu cita online con los mejores barberos de tu barrio.</p>
      </div>

      <div class="grid gap-4 sm:grid-cols-2 text-left">
        <div class="rounded-3xl border border-slate-800 p-6 bg-slate-900/80">
          <h2 class="text-xl font-semibold">Horario inteligente</h2>
          <p class="mt-3 text-slate-400">Consulta disponibilidad real de barberos y servicios según su jornada.</p>
        </div>
        <div class="rounded-3xl border border-slate-800 p-6 bg-slate-900/80">
          <h2 class="text-xl font-semibold">Servicios flexibles</h2>
          <p class="mt-3 text-slate-400">Crea servicios con duración, precio y asigna barberos para cada nicho.</p>
        </div>
      </div>

      <div class="flex justify-center">
        <Link href="/book" class="rounded-full bg-amber-400 px-8 py-4 text-slate-950 font-semibold shadow-lg shadow-amber-500/20 hover:bg-amber-300">
          Reservar turno
        </Link>
      </div>
    </div>

  </div>
</template>
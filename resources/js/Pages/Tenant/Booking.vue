<script setup>
import { ref, watch, onMounted, computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  tenant: Object,
});

const barbers = ref([]);
const services = ref([]);
const selectedBarberId = ref(null);
const selectedServiceId = ref(null);
const selectedDate = ref(new Date().toISOString().slice(0, 10));
const availableSlots = ref([]);
const selectedSlot = ref(null);
const customerName = ref('');
const customerPhone = ref('');
const customerEmail = ref('');
const notes = ref('');
const feedback = ref('');
const loading = ref(false);
const errors = ref({});

const selectedService = computed(() => {
  return services.value.find((service) => service.id === selectedServiceId.value) || null;
});

const selectedBarber = computed(() => {
  return barbers.value.find((barber) => barber.id === selectedBarberId.value) || null;
});

async function loadBarbers() {
  const response = await window.axios.get('/api/barbers');
  barbers.value = response.data;
}

async function loadServices() {
  const response = await window.axios.get('/api/services');
  services.value = response.data;
}

async function loadAvailability() {
  feedback.value = '';
  selectedSlot.value = null;
  availableSlots.value = [];

  if (!selectedBarberId.value || !selectedServiceId.value || !selectedDate.value) {
    return;
  }

  loading.value = true;

  try {
    const response = await window.axios.get('/api/availability', {
      params: {
        barber_id: selectedBarberId.value,
        service_id: selectedServiceId.value,
        appointment_date: selectedDate.value,
      },
    });

    availableSlots.value = response.data.available_slots;
    availableSlots.value = response.data.available_slots;
    if (availableSlots.value.length === 0 || !availableSlots.value.some(s => s.available)) {
      feedback.value = 'No hay franjas disponibles en ese día. Intenta otra fecha o servicio.';
    }
  } catch (error) {
    feedback.value = error.response?.data?.message || 'No se pudo cargar la disponibilidad.';
  } finally {
    loading.value = false;
  }
}

async function submitBooking() {
  feedback.value = '';
  errors.value = {};

  if (!selectedBarberId.value || !selectedServiceId.value || !selectedSlot.value || !customerName.value || !customerPhone.value) {
    feedback.value = 'Completa todos los campos obligatorios para continuar.';
    return;
  }

  loading.value = true;

  try {
    const payload = {
      barber_id: selectedBarberId.value,
      service_id: selectedServiceId.value,
      appointment_date: selectedDate.value,
      start_time: selectedSlot.value,
      name: customerName.value,
      phone: customerPhone.value,
      email: customerEmail.value,
      notes: notes.value,
    };

    const response = await window.axios.post('/api/appointments', payload);
    feedback.value = `¡Reserva confirmada! Te esperamos el ${response.data.appointment.appointment_date} a las ${response.data.appointment.start_time}.`; 
    selectedSlot.value = null;
    customerName.value = '';
    customerPhone.value = '';
    customerEmail.value = '';
    notes.value = '';
    await loadAvailability();
  } catch (error) {
    if (error.response?.data?.errors) {
      errors.value = error.response.data.errors;
    }
    feedback.value = error.response?.data?.message || 'No se pudo crear la reserva.';
  } finally {
    loading.value = false;
  }
}

watch([selectedBarberId, selectedServiceId, selectedDate], loadAvailability);

onMounted(() => {
  loadBarbers();
  loadServices();
});
</script>

<template>
  <div class="min-h-screen bg-zinc-950 text-zinc-300 font-['Outfit'] selection:bg-zinc-800 selection:text-zinc-100 py-12 px-6 sm:px-8">
    <!-- Dynamic Focus Styles -->
    <component is="style">
      :root {
        --tenant-primary: {{ tenant.primary_color || '#f59e0b' }};
      }
      .input-premium:focus {
        border-color: var(--tenant-primary);
      }
    </component>

    <div class="mx-auto max-w-5xl space-y-12">
      <!-- Header -->
      <div class="flex flex-col gap-6 sm:flex-row sm:items-end sm:justify-between border-b border-zinc-900 pb-8">
        <div>
          <p class="text-xs uppercase tracking-widest font-semibold mb-3 text-zinc-500">{{ tenant.name }}</p>
          <h1 class="text-5xl font-['Playfair_Display'] tracking-tight text-zinc-100">Reservar turno</h1>
          <p class="mt-4 max-w-2xl text-zinc-400 font-light text-lg">Selecciona el profesional, servicio y la franja horaria de tu preferencia.</p>
        </div>
        <Link href="/" class="group flex items-center text-sm font-semibold transition-opacity hover:opacity-80" :style="{ color: tenant.primary_color || '#f59e0b' }">
          <svg class="mr-2 w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          Volver al inicio
        </Link>
      </div>

      <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
        <!-- Configuración de la cita -->
        <section class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 sm:p-10">
          <div class="space-y-8">
            <!-- Barbero -->
            <div>
              <label class="block text-xs font-semibold text-zinc-400 mb-3 uppercase tracking-wider">Profesional</label>
              <div class="relative">
                <select v-model="selectedBarberId" class="input-premium w-full appearance-none rounded-2xl border border-zinc-800 bg-zinc-950 px-5 py-4 text-zinc-200 outline-none transition-colors duration-200">
                  <option disabled value="null">Selecciona un barbero</option>
                  <option v-for="barber in barbers" :key="barber.id" :value="barber.id">{{ barber.name }}</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-zinc-500">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
              </div>
            </div>

            <!-- Servicio -->
            <div>
              <label class="block text-xs font-semibold text-zinc-400 mb-3 uppercase tracking-wider">Servicio</label>
              <div class="relative">
                <select v-model="selectedServiceId" class="input-premium w-full appearance-none rounded-2xl border border-zinc-800 bg-zinc-950 px-5 py-4 text-zinc-200 outline-none transition-colors duration-200">
                  <option disabled value="null">Selecciona un servicio</option>
                  <option v-for="service in services" :key="service.id" :value="service.id">{{ service.name }} — {{ service.duration_minutes }} min</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-zinc-500">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
              </div>
            </div>

            <!-- Fecha -->
            <div>
              <label class="block text-xs font-semibold text-zinc-400 mb-3 uppercase tracking-wider">Fecha</label>
              <input type="date" v-model="selectedDate" class="input-premium w-full rounded-2xl border border-zinc-800 bg-zinc-950 px-5 py-4 text-zinc-200 outline-none transition-colors duration-200 [color-scheme:dark]" />
            </div>

            <!-- Horas -->
            <div class="pt-6 border-t border-zinc-900">
              <div class="flex items-center justify-between mb-4">
                <label class="block text-xs font-semibold text-zinc-400 uppercase tracking-wider">Horario Disponible</label>
                <span v-if="loading" class="text-xs font-medium text-zinc-500 flex items-center">
                  <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Buscando...
                </span>
              </div>
              
              <div v-if="availableSlots.length > 0" class="grid grid-cols-3 sm:grid-cols-4 gap-3">
                <button v-for="slot in availableSlots" :key="slot.time" type="button" 
                  @click="slot.available ? selectedSlot = slot.time : null"
                  :disabled="!slot.available"
                  class="rounded-xl border px-2 py-3 text-center text-sm font-semibold transition-colors duration-200"
                  :class="[
                    !slot.available ? 'border-zinc-800/50 bg-zinc-900/30 text-zinc-600 cursor-not-allowed opacity-50 relative overflow-hidden line-through' :
                    selectedSlot === slot.time ? '' : 'border-zinc-800 bg-zinc-950 text-zinc-400 hover:bg-zinc-800'
                  ]"
                  :style="slot.available && selectedSlot === slot.time ? { borderColor: tenant.primary_color || '#f59e0b', backgroundColor: (tenant.primary_color || '#f59e0b') + '1A', color: tenant.primary_color || '#f59e0b' } : {}">
                  {{ slot.time }}
                </button>
              </div>
              
              <div v-else-if="!loading" class="rounded-2xl border border-zinc-800 bg-zinc-950 p-8 text-center">
                <svg class="mx-auto h-8 w-8 text-zinc-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-sm text-zinc-500 font-light">{{ feedback || 'Selecciona profesional, servicio y fecha para ver las franjas disponibles.' }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Formulario del Cliente -->
        <aside class="rounded-2xl border border-zinc-800 bg-zinc-900/50 p-8 sm:p-10">
          <div class="space-y-6">
            <div class="mb-8 border-b border-zinc-900 pb-6">
              <h2 class="text-2xl font-['Playfair_Display'] text-zinc-100">Tus Datos</h2>
              <p class="mt-2 text-sm text-zinc-500 font-light">Completa tu información para confirmar la reserva.</p>
            </div>

            <div>
              <label class="block text-xs font-semibold text-zinc-400 mb-3 uppercase tracking-wider">Nombre completo</label>
              <input v-model="customerName" type="text" placeholder="Ej. Juan Pérez" class="input-premium w-full rounded-2xl border border-zinc-800 bg-zinc-950 px-5 py-4 text-zinc-200 placeholder-zinc-600 outline-none transition-colors duration-200" />
            </div>

            <div>
              <label class="block text-xs font-semibold text-zinc-400 mb-3 uppercase tracking-wider">Teléfono (WhatsApp)</label>
              <input v-model="customerPhone" type="tel" placeholder="Ej. 1122334455" class="input-premium w-full rounded-2xl border border-zinc-800 bg-zinc-950 px-5 py-4 text-zinc-200 placeholder-zinc-600 outline-none transition-colors duration-200" />
            </div>

            <div>
              <label class="block text-xs font-semibold text-zinc-400 mb-3 uppercase tracking-wider">Email (opcional)</label>
              <input v-model="customerEmail" type="email" placeholder="correo@ejemplo.com" class="input-premium w-full rounded-2xl border border-zinc-800 bg-zinc-950 px-5 py-4 text-zinc-200 placeholder-zinc-600 outline-none transition-colors duration-200" />
            </div>

            <div>
              <label class="block text-xs font-semibold text-zinc-400 mb-3 uppercase tracking-wider">Notas adicionales</label>
              <textarea v-model="notes" rows="3" placeholder="Comentarios, preferencias..." class="input-premium w-full rounded-2xl border border-zinc-800 bg-zinc-950 px-5 py-4 text-zinc-200 placeholder-zinc-600 outline-none transition-colors duration-200 resize-none"></textarea>
            </div>

            <div class="pt-6">
              <button @click="submitBooking" type="button" 
                class="w-full rounded-2xl px-6 py-4 text-sm uppercase tracking-wider font-semibold text-zinc-950 transition-all duration-200 hover:brightness-110 disabled:opacity-50 disabled:hover:brightness-100 flex items-center justify-center" 
                :style="{ backgroundColor: tenant.primary_color || '#f59e0b' }" 
                :disabled="loading">
                <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-zinc-950" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                {{ loading ? 'Procesando...' : 'Confirmar Reserva' }}
              </button>

              <div v-if="feedback" class="mt-6 rounded-2xl p-4 border" :class="feedback.includes('Reservado') || feedback.includes('confirmada') ? 'bg-green-500/10 border-green-500/20 text-green-400' : 'bg-red-500/10 border-red-500/20 text-red-400'">
                <p class="text-sm font-medium text-center">{{ feedback }}</p>
              </div>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>

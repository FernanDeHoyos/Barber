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
    if (!availableSlots.value.length) {
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
    feedback.value = `Turno reservado: ${response.data.appointment.appointment_date} a las ${response.data.appointment.start_time}.`; 
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
  <div class="min-h-screen bg-slate-950 text-slate-100 py-10 px-4">
    <div class="mx-auto max-w-6xl space-y-8">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
          <p class="text-sm uppercase tracking-[0.35em] text-amber-300/80">{{ tenant.name }}</p>
          <h1 class="text-4xl font-bold">Reservar turno</h1>
          <p class="mt-2 max-w-2xl text-slate-400">Elige barbero, servicio y la franja horaria que mejor te quede.</p>
        </div>
        <Link href="/" class="text-amber-300 hover:text-amber-200">Volver al inicio</Link>
      </div>

      <div class="grid gap-8 xl:grid-cols-[1.3fr_0.7fr]">
        <section class="rounded-3xl border border-slate-800 bg-slate-900/90 p-8 shadow-xl shadow-slate-950/20">
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-semibold text-slate-300">Barbero</label>
              <select v-model="selectedBarberId" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-300">
                <option disabled value="">Selecciona un barbero</option>
                <option v-for="barber in barbers" :key="barber.id" :value="barber.id">{{ barber.name }}</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-300">Servicio</label>
              <select v-model="selectedServiceId" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-300">
                <option disabled value="">Selecciona un servicio</option>
                <option v-for="service in services" :key="service.id" :value="service.id">{{ service.name }} — {{ service.duration_minutes }} min</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-300">Fecha</label>
              <input type="date" v-model="selectedDate" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-300" />
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-300">Hora</label>
              <div class="mt-2 grid gap-2 sm:grid-cols-2">
                <button v-for="slot in availableSlots" :key="slot" type="button" @click="selectedSlot = slot"
                  :class="['rounded-2xl border px-4 py-3 text-left transition', selectedSlot === slot ? 'border-amber-300 bg-amber-400/20 text-amber-200' : 'border-slate-700 bg-slate-950 text-slate-200 hover:border-amber-300']">
                  {{ slot }}
                </button>
              </div>
              <p v-if="!availableSlots.length && !loading" class="mt-3 text-sm text-slate-500">Selecciona barbería, servicio y fecha para ver franjas disponibles.</p>
            </div>
          </div>
        </section>

        <aside class="rounded-3xl border border-slate-800 bg-slate-900/90 p-8 shadow-xl shadow-slate-950/10">
          <div class="space-y-6">
            <div>
              <h2 class="text-2xl font-semibold">Tu información</h2>
              <p class="mt-2 text-sm text-slate-400">Los datos se usarán solo para confirmar el turno.</p>
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-300">Nombre</label>
              <input v-model="customerName" type="text" placeholder="Tu nombre" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-300" />
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-300">Teléfono</label>
              <input v-model="customerPhone" type="tel" placeholder="Ej. 1122334455" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-300" />
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-300">Email (opcional)</label>
              <input v-model="customerEmail" type="email" placeholder="correo@ejemplo.com" class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-300" />
            </div>

            <div>
              <label class="block text-sm font-semibold text-slate-300">Notas</label>
              <textarea v-model="notes" rows="4" placeholder="Compra final, alergias, preferencia..." class="mt-2 w-full rounded-2xl border border-slate-700 bg-slate-950 px-4 py-3 text-slate-100 outline-none focus:border-amber-300"></textarea>
            </div>

            <div class="space-y-3">
              <button @click="submitBooking" type="button" class="w-full rounded-2xl bg-amber-400 px-5 py-4 font-semibold text-slate-950 transition hover:bg-amber-300" :disabled="loading">
                {{ loading ? 'Reservando...' : 'Confirmar turno' }}
              </button>

              <p v-if="feedback" class="text-sm text-slate-300">{{ feedback }}</p>
            </div>
          </div>
        </aside>
      </div>
    </div>
  </div>
</template>

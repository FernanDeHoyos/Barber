<x-filament-panels::page>
    <div wire:ignore>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        
        <div x-data="{
            init() {
                // Ensure FullCalendar is loaded
                if (typeof FullCalendar === 'undefined') {
                    setTimeout(() => this.init(), 100);
                    return;
                }

                let calendarEl = this.$refs.calendar;
                let calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    slotMinTime: '06:00:00',
                    slotMaxTime: '22:00:00',
                    events: @js($events),
                    locale: 'es',
                    allDaySlot: false,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week: 'Semana',
                        day: 'Día'
                    }
                });
                calendar.render();
            }
        }">
            <div x-ref="calendar" class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 dark:bg-gray-900 dark:border-gray-800" style="min-height: 600px;"></div>
        </div>
    </div>
</x-filament-panels::page>

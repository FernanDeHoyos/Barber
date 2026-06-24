<x-filament-panels::page>
    <div wire:ignore>
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        
        <style>
            /* FullCalendar Filament Integration Styles */
            .fc {
                --fc-border-color: rgba(107, 114, 128, 0.15);
                --fc-button-bg-color: #ffffff;
                --fc-button-border-color: #e5e7eb;
                --fc-button-text-color: #374151;
                --fc-button-hover-bg-color: #f9fafb;
                --fc-button-hover-border-color: #d1d5db;
                --fc-button-active-bg-color: #f3f4f6;
                --fc-button-active-border-color: #d1d5db;
                --fc-today-bg-color: rgba(245, 158, 11, 0.05);
                --fc-page-bg-color: transparent;
                --fc-neutral-bg-color: transparent;
                --fc-neutral-text-color: inherit;
                --fc-theme-standard-border-color: var(--fc-border-color);
                font-family: inherit;
            }

            .dark .fc {
                --fc-button-bg-color: #18181b; /* zinc-900 */
                --fc-button-border-color: #27272a; /* zinc-800 */
                --fc-button-text-color: #e4e4e7; /* zinc-200 */
                --fc-button-hover-bg-color: #27272a;
                --fc-button-hover-border-color: #3f3f46;
                --fc-button-active-bg-color: #27272a;
                --fc-button-active-border-color: #3f3f46;
                --fc-today-bg-color: rgba(245, 158, 11, 0.1);
            }
            
            .fc-theme-standard .fc-scrollgrid {
                border-radius: 0.75rem;
                overflow: hidden;
            }

            .fc .fc-toolbar-title {
                font-size: 1.25rem;
                font-weight: 600;
                letter-spacing: -0.025em;
            }
            
            .fc .fc-button-primary {
                border-radius: 0.5rem;
                padding: 0.4rem 1rem;
                font-weight: 500;
                text-transform: capitalize;
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
                transition: all 0.2s;
            }

            .fc .fc-button-primary:focus {
                box-shadow: 0 0 0 2px rgba(245, 158, 11, 0.3);
            }
            
            .fc-event {
                border-radius: 6px;
                font-size: 0.75rem;
                font-weight: 500;
                border: none !important;
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
                transition: transform 0.1s;
                overflow: hidden;
            }
            
            .fc-event:hover {
                transform: scale(1.02);
                z-index: 10 !important;
            }

            .fc-timegrid-event .fc-event-main {
                padding: 3px 6px;
            }
            
            .fc-col-header-cell-cushion {
                padding: 12px 8px !important;
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
                color: #71717a; /* zinc-500 */
            }

            .dark .fc-col-header-cell-cushion {
                color: #a1a1aa; /* zinc-400 */
            }

            .fc-timegrid-slot-label-cushion {
                font-size: 0.75rem;
                font-weight: 500;
                color: #71717a;
            }

            .fc-timegrid-now-indicator-line {
                border-color: #ef4444; /* red-500 */
                border-width: 2px 0 0;
            }

            .fc-timegrid-now-indicator-arrow {
                border-color: #ef4444;
                border-width: 5px 0 5px 6px;
                border-top-color: transparent;
                border-bottom-color: transparent;
            }
        </style>
        
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
                    expandRows: true,
                    nowIndicator: true,
                    slotEventOverlap: false,
                    slotDuration: '00:30:00',
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
                    },
                    eventTimeFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        meridiem: 'short'
                    }
                });
                calendar.render();
            }
        }">
            <div x-ref="calendar" class="bg-white p-6 rounded-2xl shadow-sm border border-zinc-200 dark:bg-zinc-900 dark:border-zinc-800" style="min-height: 700px;"></div>
        </div>
    </div>
</x-filament-panels::page>

<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- 📅 Calendar Section -->
            <div class="mt-8 bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">📅 Your Calendar</h2>

                <div id="calendar" class="border border-gray-300 rounded p-4 h-[700px] bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-white"></div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    events: [], // You can dynamically inject events here from Blade or JS
                });
                calendar.render();
            });
        </script>
    @endpush
</x-app-layout>

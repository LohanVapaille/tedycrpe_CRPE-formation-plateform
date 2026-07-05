@extends('layouts.app')

@section('title', 'Mon Agenda CRPE')
@section('meta_description',
'Préparez le CRPE avec Tedy Florette grâce à des cours, des ressources et un accompagnement personnalisé.')
@section('content')
    <div class="agenda-section">
        <div id="calendar-framework"></div>
    </div>

    {{-- Modale EN DEHORS de agenda-section --}}
    <div id="eventModal" class="modal-overlay" style="display: none;">
        <div class="modal-box">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2 id="modalTitle"></h2>
            <hr>
            <p><strong>Date & Heure :</strong> <span id="modalDate"></span></p>
            <p><strong>Description :</strong></p>
            <p class="modalDescription" id="modalDescription"></p>

        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/fr.global.min.js"></script>

    <script>
        function openModal(title, date, description) {
            document.getElementById('modalTitle').textContent = title;
            document.getElementById('modalDate').textContent = date;
            document.getElementById('modalDescription').textContent = description || 'Aucune description.';
            document.getElementById('eventModal').style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function closeModal() {
            document.getElementById('eventModal').style.display = 'none';
            document.body.style.overflow = '';
        }

        // Fermer en cliquant sur l'overlay
        document.getElementById('eventModal').addEventListener('click', function (e) {
            if (e.target === this) closeModal();
        });

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar-framework');
            var eventsJson = {!! json_encode($events) !!};

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                timeZone: 'Europe/Paris',
                locale: 'fr',
                firstDay: 1,
                slotMinTime: '08:00:00',
                slotMaxTime: '22:00:00',
                height: '100%',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: "Mois",
                    week: "Semaine",
                    list: "Liste"
                },
                events: eventsJson,
                eventClick: function (info) {
                    var start = info.event.start;
                    var dateStr = '';

                    if (start) {
                        // On utilise le formateur officiel de FullCalendar pour respecter la Timezone
                        dateStr = FullCalendar.formatDate(start, {
                            locale: 'fr',
                            timeZone: 'Europe/Paris', // On force le même fuseau que le calendrier
                            weekday: 'long',
                            day: 'numeric',
                            month: 'long',
                            year: 'numeric',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }

                    openModal(
                        info.event.title,
                        dateStr,
                        info.event.extendedProps.description
                    );
                }
            });

            calendar.render();
        });
    </script>
@endsection
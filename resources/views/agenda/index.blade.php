@extends('layouts.app')

@section('title', 'Mon Agenda CRPE')

@section('content')
    <div class="agenda-section">
        <div class="agenda-container">
            <!-- <h2>Mon Emploi du Temps & Événements</h2>
                <p class="agenda-subtitle">Retrouvez ici vos cours collectifs, dates clés du concours et vos rendez-vous
                    individuels privés.</p> -->

            <div id="calendar-framework"></div>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.min.css">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/locales/fr.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar-framework');

            // Récupération de la collection convertie en JSON depuis Laravel
            var eventsJson = {!! json_encode($events) !!};

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'fr', // Pour l'avoir en français
                firstDay: 1,  // Commencer la semaine le lundi
                slotMinTime: '08:00:00', // Le calendrier commence à 8h00
                slotMaxTime: '22:00:00', // Le calendrier s'arrête à 22h00
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek' // Permet de switcher de vue
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: "Mois",
                    week: "Semaine",
                    list: "Liste"
                },
                events: eventsJson,
                eventClick: function (info) {
                    // Optionnel : Une alerte ou une modale quand on clique sur un cours
                    if (info.event.extendedProps.description) {
                        alert(info.event.title + " : " + info.event.extendedProps.description);
                    }
                }
            });

            calendar.render();
        });
    </script>
@endsection
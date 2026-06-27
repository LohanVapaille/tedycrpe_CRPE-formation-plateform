<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // On récupère les événements publics et privés
        $eventsData = Event::whereNull('user_id')
            ->orWhere('user_id', $user->id)
            ->get();

        // On formate les données pour correspondre aux clés attendues par FullCalendar
        $events = $eventsData->map(function ($event) {
            // Choix de la couleur selon le type ou le statut privé/public
            $color = $event->user_id ? '#36717b' : '#89c2cd'; // Tes variables $bleuvert ou $bleuclair

            return [
                'title' => ($event->user_id ? '🔒 ' : '') . $event->title,
                'start' => $event->start_time->toIso8601String(),
                'end' => $event->end_time->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'description' => $event->description, // Donnée bonus
            ];
        });

        return view('agenda.index', compact('events'));
    }
}
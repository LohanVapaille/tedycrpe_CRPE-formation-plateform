<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgendaController extends Controller
{
    public function index()
    {
        // 1. Si l'utilisateur est connecté
        if (Auth::check()) {
            $user = Auth::user();

            $eventsData = Event::whereNull('user_id')
                ->orWhere('user_id', $user->id)
                ->get();
        }
        // 2. Si c'est un simple visiteur (non connecté)
        else {
            $eventsData = Event::whereNull('user_id')->get();
        }

        // Le reste du code de formatage pour FullCalendar reste le même !
        $events = $eventsData->map(function ($event) {
            $color = $event->user_id ? '#36717b' : '#89c2cd'; // Privé vs Public

            return [
                'title' => ($event->user_id ? '🔒 ' : '') . $event->title,
                'start' => $event->start_time->toIso8601String(),
                'end' => $event->end_time->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'description' => $event->description,
            ];
        });

        return view('agenda.index', compact('events'));
    }
}
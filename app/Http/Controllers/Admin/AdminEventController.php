<?php // app/Http/Controllers/Admin/AdminEventController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class AdminEventController extends Controller
{
    public function index()
    {
        $events = Event::with('user')->orderBy('start_time')->get();
        return view('admin.evenements.index', compact('events'));
    }

    public function create()
    {
        $eleves = User::where('is_tutor', 0)->orderBy('name')->get();
        return view('admin.evenements.form', ['event' => null, 'eleves' => $eleves]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|string',

        ]);

        Event::create($request->only('title', 'description', 'start_time', 'end_time', 'user_id', 'type'));
        return redirect()->route('admin.evenements')->with('success', 'Événement créé.');
    }

    public function edit(Event $event)
    {
        $eleves = User::where('is_tutor', 0)->orderBy('name')->get();
        return view('admin.evenements.form', compact('event', 'eleves'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|string',
        ]);

        $event->update($request->only('title', 'description', 'start_time', 'end_time', 'user_id', 'type'));
        return redirect()->route('admin.evenements')->with('success', 'Événement mis à jour.');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.evenements')->with('success', 'Événement supprimé.');
    }
}
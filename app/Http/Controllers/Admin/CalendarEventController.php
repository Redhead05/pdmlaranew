<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CalendarEventController extends Controller
{
    public function events()
    {
        $events = CalendarEvent::query()
            ->orderBy('start_date')
            ->get()
            ->map(fn (CalendarEvent $e) => [
                'id' => $e->id,
                'title' => $e->title,
                'start' => $e->all_day ? $e->start_date->format('Y-m-d') : $e->start_date->toIso8601String(),
                'end' => $e->end_date
                    ? ($e->all_day ? $e->end_date->copy()->addDay()->format('Y-m-d') : $e->end_date->toIso8601String())
                    : null,
                'allDay' => $e->all_day,
            ]);

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
        ]);

        CalendarEvent::create([
            'title' => $data['title'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'all_day' => true,
            'created_by' => Auth::id(),
        ]);

        return back()->with('success', 'Event kalender ditambahkan.');
    }

    public function destroy(CalendarEvent $event)
    {
        $event->delete();

        return back()->with('success', 'Event kalender dihapus.');
    }
}

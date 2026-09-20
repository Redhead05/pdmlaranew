<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\CalendarEvent;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('menu.asesor.dashboard');
    }

    public function calendarEvents()
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status');
        $tickets = Ticket::with(['asesor', 'admin'])
            ->when($status && in_array($status, ['open', 'in_progress', 'closed'], true), fn ($q) => $q->where('status', $status))
            ->latest()
            ->get();

        return view('menu.admin.ticket.index', compact('tickets', 'status'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['messages.user', 'asesor', 'admin']);

        return view('menu.admin.ticket.show', compact('ticket'));
    }

    public function open(Ticket $ticket)
    {
        if ($ticket->status === 'open') {
            $ticket->update([
                'status' => 'in_progress',
                'admin_id' => Auth::id(),
                'opened_at' => now(),
            ]);
        }

        return back()->with('success', 'Tiket diambil alih oleh Anda.');
    }

    public function respond(Request $request, Ticket $ticket)
    {
        $data = $request->validate([
            'pesan' => ['required', 'string'],
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'pesan' => $data['pesan'],
        ]);

        return back()->with('success', 'Balasan dikirim ke asesor.');
    }
}

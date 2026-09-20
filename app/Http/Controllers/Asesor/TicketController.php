<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', auth()->id())->latest()->get();

        return view('menu.asesor.ticket.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'deskripsi' => ['required', 'string'],
            'lampiran' => ['nullable', 'image', 'max:2048'],
        ]);

        $lampiran = null;
        if ($request->hasFile('lampiran')) {
            $ext = strtolower($request->file('lampiran')->getClientOriginalExtension() ?: 'bin');
            if (! in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true)) {
                $ext = 'bin';
            }
            $lampiran = $request->file('lampiran')->storeAs('tickets', date('Ymd_His').'_'.uniqid().'.'.$ext, 'public');
        }

        Ticket::create([
            'user_id' => auth()->id(),
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'],
            'lampiran' => $lampiran,
            'status' => 'open',
        ]);

        return back()->with('success', 'Tiket support dikirim.');
    }

    public function show(Ticket $ticket)
    {
        abort_unless($ticket->user_id === auth()->id(), 403);
        $ticket->load(['messages.user', 'admin']);

        return view('menu.asesor.ticket.show', compact('ticket'));
    }

    public function close(Ticket $ticket)
    {
        abort_unless($ticket->user_id === auth()->id(), 403);

        $ticket->update([
            'status' => 'closed',
            'closed_at' => now(),
            'closed_by' => 'asesor',
        ]);

        return back()->with('success', 'Tiket support ditutup. Terima kasih.');
    }
}

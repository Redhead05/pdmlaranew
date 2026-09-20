<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAsesor = User::role('asesor')->count();
        $sudahBertugas = DB::table('team_members')->distinct()->count('user_id');
        $belumBertugas = max(0, $totalAsesor - $sudahBertugas);

        $ticketOpen = Ticket::where('status', 'open')->count();
        $ticketInProgress = Ticket::where('status', 'in_progress')->count();
        $ticketClosed = Ticket::where('status', 'closed')->count();

        return view('menu.admin.dashboard', compact(
            'totalAsesor',
            'sudahBertugas',
            'belumBertugas',
            'ticketOpen',
            'ticketInProgress',
            'ticketClosed',
        ));
    }
}

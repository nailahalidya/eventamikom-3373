<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $organizer = Auth::user()->organizer;

        $events = Event::where('owner_type', 'organizer')
            ->where('organizer_id', $organizer->id)
            ->get();
            
        $transactions = Transaction::with('event')
            ->whereHas('event', function ($query) use ($organizer) {
                 $query->where('owner_type', 'organizer')
            ->where('organizer_id', $organizer->id);
            })
            ->latest()
            ->paginate(10);

        $totalEvents = $events->count();
        $totalTransactions = $transactions->count();
        $totalTickets = $transactions->sum('quantity');
        $totalIncome = $transactions->sum('total_price');

        $latestEvents = Event::where('owner_type', 'organizer')
            ->where('organizer_id', $organizer->id)
            ->latest()
            ->take(5)
            ->get();

        $latestTransactions = Transaction::with('event')
            ->whereHas('event', function ($query) use ($organizer) {
                $query->where('owner_type', 'organizer')
                    ->where('organizer_id', $organizer->id);
            })
            ->latest()
            ->take(5)
            ->get();

        return view('organizer.index', compact(
            'organizer',
            'totalEvents',
            'totalTransactions',
            'totalTickets',
            'totalIncome',
            'latestEvents',
            'latestTransactions'
        ));
    }
}

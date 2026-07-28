<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $organizer = Auth::user()->organizer;

        $transactions = Transaction::with('event')

            ->whereHas('event', function ($q) use ($organizer) {

                $q->where('owner_type', 'organizer')
                    ->where('organizer_id', $organizer->id);
            })

            ->latest()
            ->paginate(10);
        return view('organizer.transaction.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $organizer = Auth::user()->organizer;

        abort_if(
            $transaction->event->organizer_id !== $organizer->id,
            403
        );

        $transaction->load('event');

        return view('organizer.transaction.show', compact('transaction'));
    }

}

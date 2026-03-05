<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueController extends Controller
{
    // halaman form pengunjung (URL: /antrian)
    public function form()
    {
        return view('visitor.form');
    }

    // submit form => ticket A-001 reset harian & aman duplicate
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'purpose' => ['nullable', 'string', 'max:120'],
        ]);

        $today = now()->toDateString();

        $q = DB::transaction(function () use ($today, $data) {

            $lastSeq = Queue::whereDate('created_at', $today)
                ->lockForUpdate()
                ->max(DB::raw('CAST(SUBSTRING(ticket, 3) AS UNSIGNED)'));

            $nextSeq = $lastSeq ? $lastSeq + 1 : 1;

            $ticket = 'A-' . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

            return Queue::create([
                'ticket' => $ticket,
                'name' => $data['name'],
                'purpose' => $data['purpose'] ?? null,
                'status' => 'waiting',
            ]);
        });

        return redirect()->route('queue.status', ['ticket' => $q->ticket]);
    }

    // halaman status ticket (URL: /antrian/status/A-001)
    public function status(string $ticket)
    {
        $q = Queue::where('ticket', $ticket)->firstOrFail();
        return view('visitor.status', compact('q'));
    }
}
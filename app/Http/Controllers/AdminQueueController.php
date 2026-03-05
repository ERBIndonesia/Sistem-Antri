<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminQueueController extends Controller
{
    // dashboard admin (URL: /antrian/admin/antrian)
    public function index()
    {
        $waiting = Queue::where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->get();

        $called = Queue::where('status', 'called')
            ->orderBy('called_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('waiting', 'called'));
    }

    // panggil => status called + called_at
    public function call(int $id)
    {
        $q = Queue::findOrFail($id);

        // kalau sudah served, skip
        if ($q->status === 'served') {
            return redirect()->back();
        }

        $q->update([
            'status' => 'called',
            'called_at' => Carbon::now(),
        ]);

        // kirim pesan untuk TTS via flash session
        return redirect()->back()->with('tts', "Nomor antrian {$q->ticket} atas nama {$q->name}, silakan menuju admin.");
    }

    // selesai => status served + served_at
    public function serve(int $id)
    {
        $q = Queue::findOrFail($id);

        $q->update([
            'status' => 'served',
            'served_at' => Carbon::now(),
        ]);

        return redirect()->back();
    }
}

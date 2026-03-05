<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\AdminQueueController;
use App\Models\Queue;

// =======================
// PENGUNJUNG (ANTRIAN)
// URL: http://localhost/antrian/
// =======================
Route::get('/', [QueueController::class, 'form'])->name('queue.form');
Route::post('/queue', [QueueController::class, 'store'])->name('queue.store');
Route::get('/status/{ticket}', [QueueController::class, 'status'])->name('queue.status');

// =======================
// DISPLAY (LAYAR BESAR)
// URL: http://localhost/antrian/display
// =======================
Route::get('/display', function () {
    return view('display');
})->name('display');

Route::get('/display/data', function () {
    return Queue::where('status', 'called')
        ->orderBy('called_at', 'desc')
        ->first();
})->name('display.data');

// =======================
// ADMIN (BUTUH LOGIN)
// URL: http://localhost/antrian/admin/antrian
// =======================
Route::middleware(['auth'])->group(function () {

    // dashboard admin bawaan breeze (opsional)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // profile bawaan breeze
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // dashboard antrian admin
    Route::get('/admin/antrian', [AdminQueueController::class, 'index'])->name('admin.queue');
    Route::post('/admin/antrian/{id}/call', [AdminQueueController::class, 'call'])->name('admin.queue.call');
    Route::post('/admin/antrian/{id}/serve', [AdminQueueController::class, 'serve'])->name('admin.queue.serve');
});

require __DIR__ . '/auth.php';
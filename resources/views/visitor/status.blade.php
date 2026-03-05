<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Status Antrian</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen">
  <div class="max-w-2xl mx-auto px-4 py-10">

    <div class="flex items-center justify-between gap-3 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Status Antrian</h1>
        <p class="text-sm text-slate-600">Halaman ini refresh otomatis setiap 3 detik.</p>
      </div>

      <!-- tombol kembali ke halaman ambil antrian -->
      <a href="{{ route('queue.form') }}"
         class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">
        ← Kembali
      </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
          <div class="text-xs uppercase tracking-wide text-slate-500">Nomor</div>
          <div class="text-3xl font-extrabold text-slate-900 mt-1">{{ $q->ticket }}</div>
        </div>

        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
          <div class="text-xs uppercase tracking-wide text-slate-500">Status</div>

          @if($q->status === 'waiting')
            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-sm font-semibold">
              ⏳ Menunggu dipanggil
            </div>
          @elseif($q->status === 'called')
            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-semibold">
              📣 Dipanggil — silakan ke admin
            </div>
          @else
            <div class="mt-2 inline-flex items-center gap-2 px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-semibold">
              ✅ Selesai
            </div>
          @endif
        </div>

        <div class="sm:col-span-2 p-4 rounded-2xl border border-slate-200">
          <div class="text-xs uppercase tracking-wide text-slate-500">Nama</div>
          <div class="text-lg font-bold text-slate-900 mt-1">{{ $q->name }}</div>

          <div class="mt-3 text-xs uppercase tracking-wide text-slate-500">Keperluan</div>
          <div class="text-sm text-slate-700 mt-1">{{ $q->purpose ?? '-' }}</div>
        </div>

      </div>

      <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:items-center sm:justify-between">
        <div class="text-sm text-slate-600">
          Terakhir update: <span class="font-semibold text-slate-800">{{ now()->format('H:i:s') }}</span>
        </div>

        <!-- tombol penting: kembali ke halaman ambil antrian -->
        <a href="{{ route('queue.form') }}"
           class="inline-flex justify-center px-4 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
          Ambil Antrian Baru
        </a>
      </div>
    </div>

    <div class="mt-6 text-xs text-slate-500">
      Catatan: Jika kamu sudah selesai dilayani, status akan berubah menjadi <b>Selesai</b>.
    </div>

  </div>
</body>
</html>
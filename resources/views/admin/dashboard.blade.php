<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard Antrian (Admin)</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-slate-100 min-h-screen">
  <div class="max-w-6xl mx-auto px-4 py-8">

    <!-- Top bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-6">
      <div>
        <h1 class="text-2xl font-bold text-slate-900">Dashboard Antrian</h1>
        <p class="text-sm text-slate-600">Admin panel — panggil, layani, dan pantau antrian.</p>
      </div>

      <div class="flex items-center gap-2">
       <!-- <a href="{{ url('/dashboard') }}"
           class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">
          Dashboard
        </a> -->

        <form method="post" action="{{ route('logout') }}">
          @csrf
          <button
            class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50">
            Logout
          </button>
        </form>
      </div>
    </div>

    <!-- Main grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

      <!-- Menunggu -->
      <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
          <div>
            <h2 class="font-semibold text-slate-900">Menunggu</h2>
            <p class="text-sm text-slate-600">Antrian yang belum dipanggil.</p>
          </div>
          <span class="text-sm px-3 py-1 rounded-full bg-slate-100 text-slate-700">
            {{ $waiting->count() }} orang
          </span>
        </div>

        <div class="p-5">
          @if($waiting->isEmpty())
            <div class="text-slate-600">Tidak ada antrian menunggu.</div>
          @else
            <div class="overflow-x-auto">
              <table class="w-full text-sm">
                <thead>
                  <tr class="text-left text-slate-600">
                    <th class="py-2 pr-3">Ticket</th>
                    <th class="py-2 pr-3">Nama</th>
                    <th class="py-2 pr-3">Keperluan</th>
                    <th class="py-2 text-right">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  @foreach($waiting as $q)
                    <tr class="hover:bg-slate-50">
                      <td class="py-3 pr-3 font-extrabold text-slate-900 whitespace-nowrap">
                        {{ $q->ticket }}
                      </td>
                      <td class="py-3 pr-3 text-slate-800">
                        {{ $q->name }}
                      </td>
                      <td class="py-3 pr-3 text-slate-600">
                        {{ $q->purpose ?? '-' }}
                      </td>
                      <td class="py-3 text-right">
                        <div class="flex justify-end gap-2 flex-wrap">
                          <form method="post" action="{{ route('admin.queue.call', $q->id) }}">
                            @csrf
                            <button
                              class="px-3 py-2 rounded-xl bg-blue-600 text-white hover:bg-blue-700 transition">
                              Panggil
                            </button>
                          </form>

                          <form method="post" action="{{ route('admin.queue.serve', $q->id) }}">
                            @csrf
                            <!-- <button
                              class="px-3 py-2 rounded-xl bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 transition">
                              Selesai
                            </button> -->
                          </form>
                        </div>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      <!-- Terpanggil -->
      <div class="bg-white border border-slate-200 rounded-2xl shadow-sm">
        <div class="p-5 border-b border-slate-200">
          <h2 class="font-semibold text-slate-900">Terpanggil</h2>
          <p class="text-sm text-slate-600">Daftar panggilan terakhir.</p>
        </div>

        <div class="p-5">
          @if($called->isEmpty())
            <div class="text-slate-600">Belum ada yang dipanggil.</div>
          @else
            <ul class="space-y-3">
              @foreach($called as $q)
                <li class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                <div class="flex flex-col gap-2">
                    <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="font-extrabold text-slate-900">{{ $q->ticket }}</div>
                        <div class="text-sm text-slate-700">{{ $q->name }}</div>
                        <div class="text-xs text-slate-500">{{ $q->purpose ?? '-' }}</div>
                    </div>

                    <div class="text-xs text-slate-500 whitespace-nowrap">
                        {{ optional($q->called_at)->format('H:i:s') }}
                    </div>
                    </div>

                    <div class="flex justify-end">
                    <form method="post" action="{{ route('admin.queue.serve', $q->id) }}">
                        @csrf
                        <button
                        class="px-3 py-2 rounded-xl bg-green-600 text-white hover:bg-green-700 transition text-sm">
                        Tandai Selesai
                        </button>
                    </form>
                    </div>
                </div>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
      </div>

    </div>

    <!-- Footer hint -->
    <div class="mt-6 text-xs text-slate-500">
      Tips: suara TTS akan bunyi saat kamu klik <b>Panggil</b>.
    </div>
  </div>

  <!-- TTS -->
    <script>
    function stopTTS(){
    try { speechSynthesis.cancel(); } catch(e){}
    }

    function speak(text){
    if(!text) return;

    stopTTS();

    const u = new SpeechSynthesisUtterance(String(text));
    u.lang = "id-ID";
    u.rate = 1;
    u.pitch = 1;

    u.onerror = (e) => console.error("TTS error:", e);

    speechSynthesis.speak(u);
    }

    document.addEventListener("visibilitychange", () => {
    if(document.hidden) stopTTS();
    });

    let ttsText = @json(session('tts'));

    if(ttsText){
    // coba langsung, tapi kalau diblok browser, user tinggal klik sekali
    speak(ttsText);
    window.addEventListener("click", () => speak(ttsText), { once:true });
    }
    </script>
</body>
</html>
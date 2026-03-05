<!doctype html>
<html lang="id">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Display Antrian</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-800 text-white overflow-hidden">

<div class="h-full flex flex-col">

    <!-- HEADER -->
    <div class="text-center py-6 border-b border-white/10">
        <h1 class="text-3xl font-bold tracking-wide">SISTEM ANTRIAN</h1>
        <p class="text-sm opacity-70">Silakan tunggu hingga nomor Anda dipanggil</p>
    </div>

    <!-- MAIN DISPLAY -->
    <div class="flex-1 flex items-center justify-center">

        <div class="text-center">

            <div class="text-xl opacity-70 mb-6 tracking-widest">
                NOMOR ANTRIAN
            </div>

            <div id="ticket"
                 class="text-[180px] font-extrabold tracking-wider transition-all duration-500 scale-100">
                ---
            </div>

            <div id="name"
                 class="text-5xl mt-6 opacity-80 transition-opacity duration-500">
                Menunggu...
            </div>

        </div>

    </div>

    <!-- FOOTER LIST LAST CALL -->
    <div class="bg-black/30 backdrop-blur-md border-t border-white/10 p-6">
        <div class="text-sm mb-3 opacity-70">Panggilan Terakhir</div>
        <div id="history" class="flex gap-6 text-xl font-semibold">
            <!-- isi via JS -->
        </div>
    </div>

</div>

<script>
let lastTicket = null;

function speak(text){
    const u = new SpeechSynthesisUtterance(text);
    u.lang = "id-ID";
    u.rate = 0.95;
    speechSynthesis.cancel();
    speechSynthesis.speak(u);
}

function bell(){
    const audio = new Audio("https://actions.google.com/sounds/v1/alarms/digital_watch_alarm_long.ogg");
    audio.play();
}

function animateTicket(){
    const el = document.getElementById("ticket");
    el.classList.remove("scale-100");
    el.classList.add("scale-125");
    setTimeout(() => {
        el.classList.remove("scale-125");
        el.classList.add("scale-100");
    }, 400);
}

function loadData(){
    fetch("{{ url('/display/data') }}")
    .then(res => res.json())
    .then(data => {

        if(!data) return;

        if(lastTicket !== data.ticket){
            lastTicket = data.ticket;

            document.getElementById("ticket").innerText = data.ticket;
            document.getElementById("name").innerText = data.name;

            animateTicket();
            bell();

            setTimeout(() => {
                speak("Nomor antrian " + data.ticket + 
                      " atas nama " + data.name + 
                      ", silakan menuju admin.");
            }, 800);
        }
    });

    // ambil 5 terakhir
    fetch("{{ url('/display/data') }}")
    .then(res => res.json())
    .then(data => {
        if(!data) return;

        document.getElementById("history").innerHTML =
            `<div class="opacity-80">${data.ticket} - ${data.name}</div>`;
    });
}

setInterval(loadData, 2000);
loadData();
</script>

</body>
</html>
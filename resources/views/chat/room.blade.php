<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Obrolan Bersama {{ $nama_lawan }} | JALIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fffafb; }
        /* Kustomisasi scrollbar box chat */
        .chat-box::-webkit-scrollbar { width: 4px; }
        .chat-box::-webkit-scrollbar-thumb { background-color: #fbcfe8; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800">

    <div class="max-w-md mx-auto bg-white h-screen flex flex-col shadow-2xl relative">
        <header class="p-4 border-b border-pink-50 flex items-center gap-4 sticky top-0 bg-white/95 backdrop-blur-md z-10 shadow-sm">
            <a href="{{ route('chat.index') }}" class="text-gray-400 hover:text-gray-600 text-xl px-1">←</a>

            <div class="w-10 h-10 bg-pink-100 rounded-full overflow-hidden border border-pink-100 shrink-0">
                <img src="{{ $foto_lawan ? asset('uploads/'.$foto_lawan) : 'https://ui-avatars.com/api/?name='.urlencode($nama_lawan) }}" class="w-full h-full object-cover">
            </div>

            <div class="flex-1 min-w-0">
                <h2 class="font-bold text-gray-800 truncate text-sm">{{ $nama_lawan }}</h2>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="w-2 h-2 bg-green-400 rounded-full animate-ping"></span>
                    <span class="text-[10px] text-gray-400 font-medium">Terhubung di JALIN</span>
                </div>
            </div>
        </header>

        <div id="containerPesan" class="chat-box flex-1 overflow-y-auto p-6 space-y-4" style="background: linear-gradient(to bottom, #fffafb, #ffffff);">
            <div class="text-center text-gray-400 text-xs italic py-10">Memuat obrolan aman...</div>
        </div>

        <footer class="p-4 bg-white border-t border-pink-50 sticky bottom-0">
            <form id="formKirimPesanan" onsubmit="handleKirimPesan(event)" class="flex gap-2 items-center">
                @csrf
                <input type="hidden" name="id_obrolan" value="{{ $id_obrolan }}">
                <input type="text" id="input_isi_pesan" name="isi_pesan" autocomplete="off" required placeholder="Tulis pesan diskusi di sini..." class="flex-1 bg-gray-50/80 px-5 py-3.5 rounded-2xl border border-gray-100 outline-none text-xs focus:ring-2 focus:ring-pink-200 transition">
                <button type="submit" class="bg-[#d14d72] hover:bg-[#b03d5d] text-white px-5 py-3.5 rounded-2xl text-xs font-bold uppercase shadow-md transition transform active:scale-95">
                    Kirim
                </button>
            </form>
        </footer>
    </div>

    <script>
        const containerPesan = document.getElementById('containerPesan');
        const idObrolan = "{{ $id_obrolan }}";
        let lastMessageCount = 0;

        function muatPesanMulai() {
            fetch(`/chat/ambil-pesan/${idObrolan}`)
                .then(response => response.json())
                .then(res => {
                    if(res.status === 'success') {
                        renderBalonChat(res.data, res.user_id);
                    }
                })
                .catch(err => console.error("Gagal memuat baris chat:", err));
        }

        function renderBalonChat(daftarPesan, currentUserId) {
            let htmlContent = '';

            if(daftarPesan.length === 0) {
                htmlContent = `<div class="text-center text-gray-300 text-xs italic py-10">Mulai percakapan Anda dengan menyapa partner. 👋</div>`;
                containerPesan.innerHTML = htmlContent;
                return;
            }

            daftarPesan.forEach(p => {
                const apakahSayaPengirim = (p.id_pengirim == currentUserId);

                if (apakahSayaPengirim) {
                    htmlContent += `
                        <div class="flex flex-col items-end animate-fadeIn">
                            <div class="bg-[#d14d72] text-white text-xs px-4 py-3 rounded-2xl rounded-tr-none max-w-[80%] shadow-sm text-left leading-relaxed">
                                ${p.isi_pesan}
                            </div>
                            <span class="text-[9px] text-gray-400 mt-1 font-medium mr-1">Anda</span>
                        </div>
                    `;
                } else {
                    htmlContent += `
                        <div class="flex flex-col items-start animate-fadeIn">
                            <div class="bg-gray-100 text-gray-800 text-xs px-4 py-3 rounded-2xl rounded-tl-none max-w-[80%] shadow-inner text-left leading-relaxed">
                                ${p.isi_pesan}
                            </div>
                            <span class="text-[9px] text-gray-400 mt-1 font-medium ml-1">Lawan Bicara</span>
                        </div>
                    `;
                }
            });

            containerPesan.innerHTML = htmlContent;

            if(daftarPesan.length > lastMessageCount) {
                containerPesan.scrollTop = containerPesan.scrollHeight;
                lastMessageCount = daftarPesan.length;
            }
        }

        function handleKirimPesan(e) {
            e.preventDefault();
            const inputField = document.getElementById('input_isi_pesan');
            const pesanTeks = inputField.value.trim();

            if(!pesanTeks) return;

            const formData = new FormData();
            formData.append('id_obrolan', idObrolan);
            formData.append('isi_pesan', pesanTeks);
            formData.append('_token', "{{ csrf_token() }}");

            inputField.value = '';

            fetch('/chat/kirim', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(res => {
                if(res.status === 'success') {
                    muatPesanMulai();
                }
            })
            .catch(err => console.error("Gagal mengirim pesan:", err));
        }

        muatPesanMulai();
        setInterval(muatPesanMulai, 2000);
    </script>
</body>
</html>

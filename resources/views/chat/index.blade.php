<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JALIN Chat | Hub Obrolan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f0f2f5; }
        .chat-box::-webkit-scrollbar { width: 4px; }
        .chat-box::-webkit-scrollbar-thumb { background-color: #fbcfe8; border-radius: 10px; }
    </style>
</head>
<body class="text-gray-800 antialiased">

    <div class="flex h-screen max-w-[1600px] mx-auto bg-[#eae6df] shadow-2xl overflow-hidden">

        <aside class="w-[400px] bg-white border-r border-gray-200 flex flex-col h-full z-20">
            <header class="p-5 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-pink-100 rounded-full overflow-hidden border">
                        <img src="{{ $user->foto_profil ? asset('uploads/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama_lengkap) }}" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 class="font-bold text-xs truncate max-w-[100px]">{{ explode(' ', $user->nama_lengkap)[0] }}</h2>
                        <p class="text-[9px] text-gray-400 capitalize font-medium">{{ $user->role ?? 'User' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-1.5">
                    <button onclick="openModalNewChat()" class="w-8 h-8 flex items-center justify-center bg-pink-100 hover:bg-[#d14d72] text-[#d14d72] hover:text-white rounded-xl text-lg font-bold transition shadow-sm" title="Mulai Obrolan Baru">
                        +
                    </button>

                    @if(strtolower(Auth::user()->role) === 'vendor')
                        <a href="{{ route('vendor.dashboard') }}" class="text-[10px] font-bold bg-pink-50 text-[#d14d72] px-3 py-2 rounded-xl hover:bg-[#d14d72] hover:text-white transition">
                            Kembali
                        </a>
                    @elseif(strtolower(Auth::user()->role) === 'admin')
                        <a href="/admin/dashboard" class="text-[10px] font-bold bg-pink-50 text-[#d14d72] px-3 py-2 rounded-xl hover:bg-[#d14d72] hover:text-white transition">
                            Kembali
                        </a>
                    @else
                        <a href="/customer/dashboard" class="text-[10px] font-bold bg-pink-50 text-[#d14d72] px-3 py-2 rounded-xl hover:bg-[#d14d72] hover:text-white transition">
                            Kembali
                        </a>
                    @endif
                </div>
            </header>

            <div class="flex-1 overflow-y-auto divide-y divide-gray-100 bg-white chat-box">
                @forelse($daftarObrolan as $chat)
                <div onclick="pilihRuangChat('{{ $chat->id_obrolan }}', '{{ $chat->nama_lawan }}', '{{ $chat->foto_lawan ? asset('uploads/'.$chat->foto_lawan) : 'https://ui-avatars.com/api/?name='.urlencode($chat->nama_lawan) }}')"
                     id="room-item-{{ $chat->id_obrolan }}"
                     class="room-list-item flex items-center gap-4 p-4 cursor-pointer hover:bg-gray-50 transition border-l-4 border-transparent">

                    <div class="w-12 h-12 bg-pink-50 rounded-full overflow-hidden shrink-0">
                        <img src="{{ $chat->foto_lawan ? asset('uploads/'.$chat->foto_lawan) : 'https://ui-avatars.com/api/?name='.urlencode($chat->nama_lawan) }}" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-baseline mb-1">
                            <h3 class="font-semibold text-xs text-gray-800 truncate">{{ $chat->nama_lawan }}</h3>
                            <span class="text-[9px] text-gray-400 font-medium">{{ $chat->waktu_terakhir }}</span>
                        </div>
                        <p class="text-[11px] text-gray-500 truncate" id="last-msg-{{ $chat->id_obrolan }}">{{ $chat->pesan_terakhir }}</p>
                    </div>

                    @if($chat->unread_count > 0)
                    <div id="unread-badge-{{ $chat->id_obrolan }}" class="bg-[#d14d72] text-white text-[9px] font-bold w-4 h-4 flex items-center justify-center rounded-full">
                        {{ $chat->unread_count }}
                    </div>
                    @endif
                </div>
                @empty
                <div class="p-12 text-center text-gray-400 italic text-xs mt-10 space-y-2">
                    <span class="text-xl">💬</span> <p>Belum ada daftar chat aktif.</p>
                </div>
                @endforelse
            </div>
        </aside>

        <main class="flex-1 flex flex-col h-full bg-[#efeae2] relative">

            <div id="viewKosongChat" class="absolute inset-0 bg-[#f8f9fa] flex flex-col items-center justify-center text-center p-8 z-10">
                <div class="w-48 h-48 mb-6 opacity-80 bg-pink-50 rounded-full flex items-center justify-center text-6xl">💍</div>
                <h2 class="text-xl font-bold text-gray-700">JALIN Hub Chat</h2>
                <p class="text-xs text-gray-400 max-w-sm mt-2 leading-relaxed">Pilih salah satu kontak di sebelah kiri atau klik tombol plus (+) untuk memulai diskusi baru bersama partner JALIN.</p>
                <div class="mt-8 text-[10px] text-gray-300 font-medium tracking-widest uppercase border-t pt-4 w-40">End-to-End Secure</div>
            </div>

            <div id="viewAktifChat" class="hidden flex-col h-full w-full">
                <header class="p-4 bg-gray-50 border-b border-gray-200 flex items-center gap-4 shadow-sm z-10">
                    <div class="w-10 h-10 bg-pink-100 rounded-full overflow-hidden">
                        <img id="header_foto_lawan" src="" class="w-full h-full object-cover">
                    </div>
                    <div>
                        <h2 id="header_nama_lawan" class="font-bold text-sm text-gray-800">Nama Lawan</h2>
                        <p class="text-[10px] text-green-500 font-semibold mt-0.5 flex items-center gap-1">
                            <span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span> Terhubung di JALIN
                        </p>
                    </div>
                </header>

                <div id="containerPesan" class="chat-box flex-1 overflow-y-auto p-6 space-y-4 bg-white">
                </div>

                <footer class="p-4 bg-gray-50 border-t border-gray-200">
                    <form id="formKirimPesanan" onsubmit="handleKirimPesan(event)" class="flex gap-2 items-center">
                        @csrf
                        <input type="hidden" id="active_id_obrolan" name="id_obrolan" value="">
                        <input type="text" id="input_isi_pesan" name="isi_pesan" autocomplete="off" required placeholder="Tulis pesan diskusi di sini..." class="flex-1 bg-white px-5 py-3.5 rounded-xl border border-gray-200 outline-none text-xs focus:ring-2 focus:ring-pink-200 transition">
                        <button type="submit" class="bg-[#d14d72] hover:bg-[#b03d5d] text-white px-6 py-3.5 rounded-xl text-xs font-bold uppercase shadow-sm transition transform active:scale-95">
                            Kirim
                        </button>
                    </form>
                </footer>
            </div>

        </main>
    </div>

    <div id="modalNewChat" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm">
        <div class="bg-white w-full max-w-sm rounded-3xl p-6 shadow-2xl flex flex-col max-h-[75vh]">
            <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                <h3 class="font-bold text-sm text-gray-800">Mulai Obrolan Baru</h3>
                <button onclick="closeModalNewChat()" class="text-gray-400 hover:text-gray-600 font-bold text-sm px-2">✕</button>
            </div>

            <div class="flex-1 overflow-y-auto divide-y divide-gray-50 mt-3 chat-box">
                @forelse($listAllKontak as $k)
                <form action="{{ route('chat.mulai', $k->id) }}" method="POST" class="w-full">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 p-3 text-left hover:bg-pink-50/40 rounded-xl transition group">
                        <div class="w-9 h-9 bg-pink-100 rounded-full overflow-hidden shrink-0">
                            <img src="{{ $k->foto_profil ? asset('uploads/'.$k->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($k->nama_toko ?? $k->nama_lengkap) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0 flex-1">
                            <h4 class="font-semibold text-xs text-gray-800 truncate group-hover:text-[#d14d72] transition">{{ $k->nama_toko ?? $k->nama_lengkap }}</h4>
                            <p class="text-[10px] text-gray-400 truncate mt-0.5">{{ $k->role }} | {{ $k->alamat ?? 'User JALIN Hub' }}</p>
                        </div>
                    </button>
                </form>
                @empty
                <div class="p-8 text-center text-gray-400 italic text-xs">Belum ada pengguna lain untuk dihubungi.</div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        let currentRoomId = null;
        let intervalPolling = null;
        let lastMessageCount = 0;

        function openModalNewChat() {
            const modal = document.getElementById('modalNewChat');
            if(modal) { modal.classList.remove('hidden'); modal.classList.add('flex'); }
        }

        function closeModalNewChat() {
            const modal = document.getElementById('modalNewChat');
            if(modal) { modal.classList.remove('flex'); modal.classList.add('hidden'); }
        }

        function pilihRuangChat(idObrolan, namaLawan, fotoLawan) {
            currentRoomId = idObrolan;
            lastMessageCount = 0;

            document.getElementById('viewKosongChat').classList.add('hidden');
            document.getElementById('viewAktifChat').classList.remove('hidden');

            document.getElementById('header_nama_lawan').innerText = namaLawan;
            document.getElementById('header_foto_lawan').src = fotoLawan;
            document.getElementById('active_id_obrolan').value = idObrolan;

            document.querySelectorAll('.room-list-item').forEach(item => {
                item.classList.remove('bg-pink-50/40', 'border-[#d14d72]');
            });
            const activeItem = document.getElementById('room-item-' + idObrolan);
            if(activeItem) activeItem.classList.add('bg-pink-50/40', 'border-[#d14d72]');

            const unreadBadge = document.getElementById('unread-badge-' + idObrolan);
            if(unreadBadge) unreadBadge.remove();

            if(intervalPolling) clearInterval(intervalPolling);
            muatPesanMulai();
            intervalPolling = setInterval(muatPesanMulai, 2000);
        }

        function muatPesanMulai() {
            if(!currentRoomId) return;

            fetch(`/chat/ambil-pesan/${currentRoomId}`)
                .then(response => response.json())
                .then(res => {
                    if(res.status === 'success') {
                        renderBalonChat(res.data, res.user_id);
                    }
                })
                .catch(err => console.error("Gagal sinkronisasi chat:", err));
        }

        function renderBalonChat(daftarPesan, currentUserId) {
            const containerPesan = document.getElementById('containerPesan');
            let htmlContent = '';

            if(daftarPesan.length === 0) {
                containerPesan.innerHTML = `<div class="text-center text-gray-400 text-xs italic py-10">Mulai diskusi aman Anda di sini. 👋</div>`;
                return;
            }

            daftarPesan.forEach(p => {
                const apakahSayaPengirim = (p.id_pengirim == currentUserId);

                if (apakahSayaPengirim) {
                    htmlContent += `
                        <div class="flex flex-col items-end w-full">
                            <div class="bg-[#d14d72] text-white text-xs px-4 py-2.5 rounded-xl rounded-tr-none max-w-[75%] shadow-sm text-left">
                                ${p.isi_pesan}
                            </div>
                            <span class="text-[8px] text-gray-400 mt-0.5 mr-0.5">Anda</span>
                        </div>
                    `;
                } else {
                    htmlContent += `
                        <div class="flex flex-col items-start w-full">
                            <div class="bg-gray-100 text-gray-800 text-xs px-4 py-2.5 rounded-xl rounded-tl-none max-w-[75%] shadow-sm text-left">
                                ${p.isi_pesan}
                            </div>
                            <span class="text-[8px] text-gray-400 mt-0.5 ml-0.5">Lawan Bicara</span>
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

            if(!pesanTeks || !currentRoomId) return;

            const formData = new FormData();
            formData.append('id_obrolan', currentRoomId);
            formData.append('isi_pesan', pesanTeks);
            formData.append('_token', "{{ csrf_token() }}");

            inputField.value = '';

            const lastMsgLabel = document.getElementById('last-msg-' + currentRoomId);
            if(lastMsgLabel) lastMsgLabel.innerText = pesanTeks;

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
            .catch(err => console.error("Gagal mengirim data pesan:", err));
        }
    </script>
</body>
</html>

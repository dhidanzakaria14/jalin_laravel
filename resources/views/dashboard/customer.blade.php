<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎀 JALIN Hub - One Stop Wedding Marketplace 🎀</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Quicksand:wght=500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: radial-gradient(circle, #fff0f3 0%, #ffe5ec 100%);
        }
        .font-cute { font-family: 'Fredoka One', cursive; }
        .bouncing:hover { transform: scale(1.03); transition: 0.2s; }
        /* Animasi haluss untuk balon chat AI */
        .chat-bubble-fade { animation: fadeInUp 0.3s ease-out forwards; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="text-gray-700 text-left pb-12 selection:bg-pink-200 relative min-h-screen">

    <nav class="bg-white/80 backdrop-blur-md py-4 shadow-md sticky top-0 z-50 border-b-4 border-dashed border-pink-200">
        <div class="w-[90%] mx-auto flex justify-between items-center">
            <div class="text-3xl font-cute text-[#d14d72] tracking-wider cursor-pointer transform hover:scale-110 transition" onclick="showTab('beranda')">
                ✨JALIN<span class="text-yellow-400">.</span>✨
            </div>
            <div class="flex items-center gap-6 text-sm font-bold text-[#d14d72]">
                <button onclick="showTab('beranda')" class="hover:text-pink-400 transition bg-pink-50 px-3 py-1.5 rounded-xl border border-pink-100">🏠 Beranda</button>

                <button onclick="showTab('keranjang')" class="relative bouncing bg-orange-50 hover:bg-orange-100 p-2.5 rounded-full border border-orange-200 transition text-xl flex items-center justify-center shadow-sm">
                    🛒
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-cute px-1.5 py-0.5 rounded-full animate-pulse">1</span>
                </button>

                <div class="flex items-center gap-2 bg-gradient-to-r from-[#ffb7c5] to-[#d14d72] px-5 py-2.5 rounded-2xl text-white shadow-lg font-cute tracking-wide">
                    👑 <span>{{ strtoupper($user->nama_lengkap) }}</span> 👑
                </div>
            </div>
        </div>
    </nav>

    <main class="w-[90%] mx-auto py-10">

        @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border-2 border-dashed border-green-400 text-green-700 rounded-2xl font-bold text-sm text-center shadow-sm">
            {{ session('success') }}
        </div>
        @endif

        <div class="flex flex-col lg:flex-row gap-8">

            <aside class="w-full lg:w-1/4 space-y-6">
                <div class="bg-white p-8 rounded-[3rem] shadow-xl text-center border-4 border-[#ffb7c5] relative overflow-hidden">
                    <div class="absolute -top-3 -right-3 text-3xl">🌸</div>
                    <div class="absolute -bottom-3 -left-3 text-3xl">🎈</div>

                    <div class="w-28 h-28 bg-gradient-to-tr from-yellow-100 to-pink-200 rounded-full mx-auto mb-4 flex items-center justify-center border-4 border-dashed border-[#d14d72] overflow-hidden shadow-inner">
                        @if(!empty($user->foto_profil) && file_exists(public_path('uploads/' . $user->foto_profil)))
                            <img src="{{ asset('uploads/' . $user->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-4xl animate-bounce">✨👤✨</span>
                        @endif
                    </div>

                    <h2 class="text-2xl font-cute text-pink-600 italic tracking-wide">💕 {{ $user->nama_lengkap }} 💕</h2>
                    <span class="inline-block bg-yellow-100 text-yellow-700 text-[10px] font-extrabold uppercase tracking-widest px-3 py-1 rounded-full border border-yellow-200 mt-1">
                        ✨ {{ $user->role ?? 'Calon Pengantin' }} ✨
                    </span>

                    <hr class="my-6 border-2 border-dashed border-pink-100">

                    <div class="space-y-3 text-left">
                        <div class="text-[10px] font-cute text-pink-400 uppercase tracking-[0.2em] mb-2 ml-2">🧁 Menu Navigasi</div>

                        <button type="button" onclick="showTab('beranda')" id="btn-beranda" class="w-full text-sm font-cute text-center text-white bg-gradient-to-r from-[#d14d72] to-pink-400 p-4 rounded-2xl shadow-md transition border-b-4 border-[#b03d5d]">
                            ✨ Beranda & Katalog
                        </button>

                        <button type="button" onclick="showTab('keranjang')" id="btn-keranjang" class="w-full bouncing block text-sm font-cute text-center text-orange-600 bg-orange-50 hover:bg-orange-400 hover:text-white p-4 rounded-2xl transition border-2 border-dashed border-orange-400 shadow-sm">
                            🛒 Keranjang Belanja
                        </button>

                        <button type="button" onclick="showTab('pesanan')" id="btn-pesanan" class="w-full bouncing block text-sm font-cute text-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white p-4 rounded-2xl transition border-2 border-dashed border-blue-400 shadow-sm">
                            📦 Pesanan Saya
                        </button>

                        <a href="{{ route('chat.index') }}" class="w-full bouncing block text-sm font-cute text-center text-[#d14d72] bg-pink-50 hover:bg-[#d14d72] hover:text-white p-4 rounded-2xl transition border-2 border-dashed border-[#d14d72] shadow-sm">
                            💬 Ruang Diskusi Chat
                        </a>

                        <button type="button" onclick="showTab('edit-profile')" id="btn-edit-profile" class="w-full bouncing block text-sm font-bold text-center text-gray-500 hover:text-pink-500 hover:bg-yellow-50 p-3 rounded-2xl transition border border-gray-200">
                            ⚙️ Pengaturan Profil
                        </button>

                        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Yakin mau keluar, Kak?🥺')">
                            @csrf
                            <button type="submit" class="w-full bouncing text-center text-sm font-cute text-white bg-red-400 hover:bg-red-500 p-3 rounded-2xl transition border-b-4 border-red-600 shadow-md">
                                🚪 Keluar (Logout)
                            </button>
                        </form>
                    </div>
                </div>
            </aside>

            <section class="flex-1 space-y-8">

                <div id="tab-beranda" class="tab-content space-y-8">
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-pink-100 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="text-left w-full">
                            <h1 class="text-3xl font-cute text-gray-800">Halo, Kak <span class="italic text-[#d14d72]">{{ $user->nama_lengkap }}</span>! 👋🎈</h1>
                            <p class="text-pink-500 text-sm font-bold mt-1">✨ Hari bahagia pernikahan impianmu sedang kami racik dengan cinta! ✨</p>
                        </div>
                        <div class="text-center bg-gradient-to-b from-yellow-50 to-yellow-100 px-6 py-5 rounded-[2rem] border-4 border-dashed border-yellow-400 min-w-[120px] shadow-md">
                            <div class="text-4xl font-cute text-[#d14d72]">{{ $countBooking }}</div>
                            <div class="text-[10px] text-yellow-700 uppercase font-cute tracking-wider mt-1">🎉 Bookings</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-white p-6 rounded-[2.5rem] shadow-lg border-4 border-purple-100 text-left">
                            <h4 class="font-cute text-purple-500 text-xs tracking-wider uppercase mb-4">💰 Alokasi Dana Celengan JALIN</h4>
                            <div class="h-48 flex items-center justify-center">
                                <canvas id="chartBiaya"></canvas>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-[2.5rem] shadow-lg border-4 border-blue-100 text-left">
                            <h4 class="font-cute text-blue-500 text-xs tracking-wider uppercase mb-4">📈 Tren Intensitas Keaktifan Vendor</h4>
                            <div class="h-48 flex items-center justify-center">
                                <canvas id="chartTren"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-orange-100 text-left relative">
                        <div class="absolute -top-4 left-6 bg-orange-400 text-white font-cute text-xs px-4 py-1.5 rounded-full shadow-md">🛍️ REKOMENDASI KATALOG JALIN</div>
                        <h3 class="font-cute text-gray-400 tracking-widest uppercase text-xs mb-6 mt-2">🛍️ Belanja Kebutuhan Jasa Vendor</h3>

                        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                            @forelse($katalogLayanan as $layanan)
                            <div class="bg-white rounded-3xl border-2 border-orange-50 overflow-hidden shadow-sm hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between">
                                <div class="w-full h-44 bg-pink-50 relative overflow-hidden">
                                    @if(!empty($layanan->gambar))
                                        <img src="{{ asset('uploads/' . $layanan->gambar) }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://placehold.co/400x300/fff0f3/d14d72?text=JALIN+Vendor+Item';">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-4xl bg-orange-50 text-orange-300">📦</div>
                                    @endif
                                    <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-[9px] font-cute text-orange-600 px-2.5 py-1 rounded-full shadow-sm">⭐ 4.9</span>
                                </div>

                                <div class="p-4 flex-1 flex flex-col justify-between">
                                    <div class="space-y-1">
                                        <p class="text-[10px] text-pink-500 font-cute tracking-wide uppercase">🏪 {{ $layanan->nama_toko ?? 'Mitra JALIN' }}</p>
                                        <h4 class="font-bold text-sm text-gray-800 line-clamp-2 min-h-[40px] leading-tight">{{ $layanan->nama_layanan }}</h4>
                                    </div>
                                    <div class="mt-4 pt-3 border-t border-dashed border-gray-100 flex items-center justify-between gap-2">
                                        <div>
                                            <p class="text-[8px] text-gray-400 font-bold uppercase tracking-wider">Harga Jasa</p>
                                            <p class="text-sm font-cute text-[#d14d72]">Rp {{ number_format($layanan->harga ?? 0, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="flex items-center gap-1.5">
                                            <button type="button" onclick="alert('Berhasil ditambah ke keranjang imut! 🛒✨')" class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-xl text-xs font-bold shadow-sm transition active:scale-95">🛒+</button>
                                            <form action="{{ route('chat.mulai', $layanan->id_vendor) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-orange-400 hover:bg-orange-500 text-white text-[10px] font-cute px-3 py-2 rounded-xl shadow-sm transition flex items-center gap-1"><span>💬</span> Chat</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-span-full py-10 text-center text-gray-400 text-xs italic">Belum ada item katalog terdaftar hari ini.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <div id="tab-keranjang" class="tab-content hidden space-y-8">
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-orange-200 text-left relative">
                        <div class="absolute -top-4 left-6 bg-orange-400 text-white font-cute text-xs px-4 py-1.5 rounded-full shadow-md">🛒 KERANJANG BELANJAANMU</div>
                        <h3 class="font-cute text-gray-700 text-lg mb-4 mt-2">Daftar Paket Jasa Siap Checkout</h3>

                        <div class="space-y-4">
                            <div class="flex flex-col sm:flex-row items-center justify-between p-4 bg-orange-50/40 rounded-2xl border-2 border-dashed border-orange-100 gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-pink-100 rounded-xl overflow-hidden font-cute flex items-center justify-center text-2xl">💍</div>
                                    <div>
                                        <h4 class="font-bold text-sm text-gray-800">Paket Wedding Luxury Gold</h4>
                                        <p class="text-[10px] text-gray-400">Vendor: Catering Joss</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="font-cute text-[#d14d72] text-sm">Rp 15.000.000</span>
                                    <button type="button" onclick="this.parentElement.parentElement.remove()" class="text-red-400 hover:text-red-600 font-bold text-xs bg-white p-2 rounded-xl shadow-sm">🗑️ Hapus</button>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-dashed border-gray-100 flex justify-between items-center">
                            <div>
                                <p class="text-[10px] text-gray-400 font-bold">TOTAL ESTIMASI</p>
                                <p class="text-xl font-cute text-[#d14d72]">Rp 15.000.000</p>
                            </div>
                            <button type="button" onclick="alert('Layanan pesanan sukses diproses! Silakan koordinasi via chat JALIN Hub ✨')" class="bg-gradient-to-r from-orange-400 to-red-400 text-white font-cute text-xs px-6 py-3 rounded-xl shadow-md border-b-4 border-orange-600">
                                🚀 Checkout Sekarang
                            </button>
                        </div>
                    </div>
                </div>

                <div id="tab-pesanan" class="tab-content hidden space-y-8">
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-blue-100 text-left relative">
                        <div class="absolute -top-4 left-6 bg-blue-400 text-white font-cute text-xs px-4 py-1.5 rounded-full shadow-md">📦 MONITOR PESANAN ANDA</div>
                        <h3 class="font-cute text-gray-400 tracking-widest uppercase text-xs mb-6 mt-2">🧁 Riwayat Pesanan Jasa</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[11px] font-cute text-blue-400 uppercase tracking-[0.2em] border-b-2 border-dashed border-gray-100">
                                        <th class="pb-4 pl-2">Mitra Vendor</th>
                                        <th class="pb-4">Paket Jasa</th>
                                        <th class="pb-4 text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm font-medium">
                                    @forelse($queryOrders as $row)
                                    <tr class="border-b border-gray-100 last:border-0 hover:bg-blue-50/30 transition">
                                        <td class="py-5 pl-2 font-cute text-gray-700 text-xs text-blue-600">{{ $row->nama_vendor }}</td>
                                        <td class="py-5 text-gray-500 text-xs font-bold">{{ $row->nama_layanan }}</td>
                                        <td class="py-5 text-center">
                                            <span class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-[9px] font-cute tracking-wider border border-blue-200">💖 {{ $row->status }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="3" class="py-12 text-center text-gray-400 text-xs italic">Belum ada riwayat pesanan.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-purple-100 text-left relative">
                        <div class="absolute -top-4 left-6 bg-purple-400 text-white font-cute text-xs px-4 py-1.5 rounded-full shadow-md">HISTORI NOMINAL</div>
                        <h3 class="font-cute text-gray-400 tracking-widest uppercase text-xs mb-6 mt-2">💸 Jejak Pembayaran Terdata</h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="text-[11px] font-cute text-purple-400 uppercase tracking-[0.2em] border-b-2 border-dashed border-gray-100">
                                        <th class="pb-4 pl-2">Layanan</th>
                                        <th class="pb-4">Metode</th>
                                        <th class="pb-4">Nominal</th>
                                        <th class="pb-4 text-right">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="text-sm font-medium">
                                    @forelse($queryPayment as $pay)
                                    <tr class="border-b border-gray-100 last:border-0 hover:bg-purple-50/30 transition">
                                        <td class="py-5 pl-2 font-cute text-gray-700 text-xs">{{ $pay->nama_layanan }}</td>
                                        <td class="py-5 text-gray-400 text-xs font-bold uppercase">{{ $pay->metode_bayar }}</td>
                                        <td class="py-5 text-[#d14d72] font-cute text-xs">Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td class="py-5 text-right">
                                            <span class="px-3 py-1.5 rounded-full text-[9px] font-cute {{ $pay->status_pembayaran == 'Terkonfirmasi' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-orange-100 text-orange-700 border border-orange-200' }}">🍭 {{ $pay->status_pembayaran }}</span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="py-12 text-center text-gray-400 text-xs italic">Belum ada riwayat nominal pembayaran.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div id="tab-edit-profile" class="tab-content hidden space-y-8">
                    <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-yellow-300 text-left relative">
                        <div class="absolute -top-4 left-6 bg-yellow-400 text-white font-cute text-xs px-4 py-1.5 rounded-full shadow-md">⚙️ PENGATURAN DATA PROFIL SAYA</div>
                        <h3 class="font-cute text-gray-700 tracking-wide text-lg mb-4 mt-2">Lengkapi & Perbarui Data Akun Anda</h3>

                        <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-1">
                                    <label class="text-[11px] font-cute text-[#d14d72]">💕 NAMA LENGKAP</label>
                                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}" class="w-full text-xs px-4 py-3 rounded-xl border-2 border-pink-100 focus:border-pink-400 focus:outline-none font-medium" required>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-cute text-[#d14d72]">📞 NOMOR TELEPON / WA</label>
                                    <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp ?? '') }}" placeholder="Contoh: 081234567xx" class="w-full text-xs px-4 py-3 rounded-xl border-2 border-pink-100 focus:border-pink-400 focus:outline-none font-medium">
                                </div>
                                <div class="space-y-1 md:col-span-2">
                                    <label class="text-[11px] font-cute text-[#d14d72]">🏠 ALAMAT LENGKAP TEMPAT TINGGAL</label>
                                    <textarea name="alamat" rows="2" placeholder="Tulis alamat rumah lengkap saat ini..." class="w-full text-xs px-4 py-3 rounded-xl border-2 border-pink-100 focus:border-pink-400 focus:outline-none font-medium">{{ old('alamat', $user->alamat ?? '') }}</textarea>
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-cute text-gray-400">🔒 PASSWORD BARU</label>
                                    <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full text-xs px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-pink-400 focus:outline-none font-medium">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[11px] font-cute text-gray-400">🔄 KONFIRMASI PASSWORD BARU</label>
                                    <input type="password" name="password_confirmation" placeholder="Ulangi password baru..." class="w-full text-xs px-4 py-3 rounded-xl border-2 border-gray-100 focus:border-pink-400 focus:outline-none font-medium">
                                </div>
                                <div class="space-y-1 md:col-span-2 bg-pink-50/40 p-4 rounded-2xl border-2 border-dashed border-pink-100">
                                    <label class="text-[11px] font-cute text-[#d14d72] block mb-2">📸 PERBARUI FOTO PROFIL</label>
                                    <input type="file" name="foto_profil" class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:bg-pink-100 file:text-pink-700 hover:file:bg-pink-200 cursor-pointer font-bold">
                                </div>
                            </div>
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-[#d14d72] to-pink-400 text-white font-cute text-xs px-8 py-3 rounded-xl shadow-md border-b-4 border-[#b03d5d] bouncing transition">💾 Simpan Perubahan Akun</button>
                            </div>
                        </form>
                    </div>
                </div>

            </section>
        </div>
    </main>

    <div class="fixed bottom-6 right-6 z-50 flex flex-col items-end">
        <div id="aiChatBox" class="hidden w-80 sm:w-96 h-[450px] bg-white rounded-[2.5rem] shadow-2xl border-4 border-pink-300 flex flex-col overflow-hidden mb-3 chat-bubble-fade">
            <div class="bg-gradient-to-r from-[#ffb7c5] to-[#d14d72] p-4 text-white flex justify-between items-center border-b-4 border-dashed border-pink-200">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">🤖</span>
                    <div class="text-left">
                        <h4 class="font-cute text-sm tracking-wide">JALIN AI Assistant</h4>
                        <p class="text-[9px] font-bold text-yellow-100 uppercase tracking-widest">Konsultan Nikah Pintar</p>
                    </div>
                </div>
                <button type="button" onclick="toggleAiChat()" class="text-white hover:text-yellow-200 font-bold text-xs bg-black/10 px-2.5 py-1 rounded-xl">❌ Minimize</button>
            </div>

            <div id="aiChatMessages" class="flex-1 p-4 overflow-y-auto space-y-3 bg-pink-50/20 text-xs text-left">
                <div class="flex items-start gap-2">
                    <div class="bg-white p-2.5 rounded-2xl rounded-tl-none border border-pink-100 shadow-sm max-w-[80%]">
                        Halo Kakak Pengantin! 🌸 Aku **JALIN AI**, konsultan pernikahan pintarmu. Ada yang bisa aku bantu seputar katering, dekorasi, gaun, atau tips hemat budget nikah? 💕
                    </div>
                </div>
            </div>

            <div class="p-3 bg-white border-t border-gray-100 flex gap-1.5 items-center">
                <input type="text" id="aiInputText" placeholder="Tanya tips hemat, katering, dll..." class="flex-1 text-xs px-3 py-2.5 rounded-xl border-2 border-pink-100 focus:border-pink-400 focus:outline-none" onkeypress="if(event.key === 'Enter') sendAiMessage()">
                <button type="button" onclick="sendAiMessage()" class="bg-[#d14d72] hover:bg-pink-600 text-white font-cute text-xs px-4 py-2.5 rounded-xl shadow-sm transition">Kirim</button>
            </div>
        </div>

        <button type="button" onclick="toggleAiChat()" class="w-14 h-14 bg-gradient-to-tr from-[#ffb7c5] to-[#d14d72] rounded-full shadow-2xl flex items-center justify-center border-4 border-white text-3xl animate-bounce hover:scale-110 transition active:scale-95" title="Tanya JALIN AI">
            🤖
        </button>
    </div>

    <script>
        // --- 🤖 ENGINE ROBOT INTERAKTIF JALIN AI (SIMULASI CLIENT-SIDE) ---
        function toggleAiChat() {
            var box = document.getElementById('aiChatBox');
            box.classList.toggle('hidden');
            if(!box.classList.contains('hidden')) {
                var msgDiv = document.getElementById('aiChatMessages');
                msgDiv.scrollTop = msgDiv.scrollHeight;
            }
        }

        function sendAiMessage() {
            var input = document.getElementById('aiInputText');
            var text = input.value.trim();
            if (text === "") return;

            var msgDiv = document.getElementById('aiChatMessages');

            // 1. Render Pesan Ketikan Customer ke Layar
            var userHtml = `<div class="flex justify-end"><div class="bg-gradient-to-r from-[#ffb7c5] to-[#d14d72] text-white p-2.5 rounded-2xl rounded-tr-none shadow-sm max-w-[80%] font-medium">${text}</div></div>`;
            msgDiv.innerHTML += userHtml;
            input.value = "";
            msgDiv.scrollTop = msgDiv.scrollHeight;

            // 2. Prosedur Berpikir Simulasi AI (Delay Ringan 0.8 Detik)
            setTimeout(function() {
                var response = "Wah, pertanyaan yang bagus Kak! ✨ Terkait hal tersebut, aku rekomendasikan Kakak berdiskusi langsung dengan Vendor Mitra JALIN di menu ruang chat diskusi ya, biar dapat penawaran harga paket yang pas dan diskon spesial! 💖";

                // Aturan Kamus Kata Kunci (Keywords) Jawaban AI JALIN Pintar
                var lowerText = text.toLowerCase();
                if (lowerText.includes('hemat') || lowerText.includes('murah') || lowerText.includes('budget')) {
                    response = "💡 **Tips Hemat ala JALIN AI:**\n1. Pilih paket wedding bundling (Katering + Dekor sekaligus).\n2. Pangkas tamu undangan fisik dan maksimalkan undangan digital.\n3. Cari vendor UMKM lokal yang ada di katalog JALIN karena harganya jauh lebih bersahabat! 🌸";
                } else if (lowerText.includes('katering') || lowerText.includes('makan')) {
                    response = "🍗 **Rekomendasi Katering:** Di katalog JALIN ada vendor katering top terpercaya! Saranku, alokasikan sekitar 40% dari total dana nikah untuk konsumsi, dan pilih menu prasmanan biar tamu bebas memilih rasa favorit mereka! 🍱";
                } else if (lowerText.includes('dekor') || lowerText.includes('bunga') || lowerText.includes('panggung')) {
                    response = "💐 **Tips Dekorasi Cantik:** Tema *Rustic Pastel* atau *Minimalis Modern* sekarang lagi tren loh Kak, dan harganya relatif lebih murah karena minimalis tapi tetep kelihatan mewah dan estetik pas difoto! 📸";
                } else if (lowerText.includes('baju') || lowerText.includes('gaun') || lowerText.includes('rias') || lowerText.includes('makeup')) {
                    response = "👑 **Tips Rias Pengantin:** Pastikan Kakak melakukan *Test Makeup* ke vendor pilihan H-1 bulan sebelum acara ya. Di menu katalog JALIN, Kakak bisa cek portofolio MUA terbaik dengan rating bintang 5!";
                }

                // Format text ganti baris (\n) menjadi tag HTML break (<br>)
                response = response.replace(/\n/g, "<br>");

                // 3. Render Jawaban Robot AI ke Layar
                var aiHtml = `<div class="flex items-start gap-2"><div class="bg-white p-2.5 rounded-2xl rounded-tl-none border border-pink-100 shadow-sm max-w-[80%]">🤖 ${response}</div></div>`;
                msgDiv.innerHTML += aiHtml;
                msgDiv.scrollTop = msgDiv.scrollHeight;
            }, 800);
        }

        // --- 📊 LOGIC DATA GRAFIK CHART.JS ---
        document.addEventListener("DOMContentLoaded", function() {
            const ctxBiaya = document.getElementById('chartBiaya').getContext('2d');
            new Chart(ctxBiaya, {
                type: 'bar',
                data: {
                    labels: ['Catering', 'Dekorasi', 'Rias Pengantin', 'Undangan'],
                    datasets: [{
                        label: 'Anggaran Dana (Juta)',
                        data: [15, 25, 8, 3],
                        backgroundColor: ['#ffb7c5', '#d14d72', '#b03d5d', '#facc15'],
                        borderRadius: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } }
                }
            });

            const ctxTren = document.getElementById('chartTren').getContext('2d');
            new Chart(ctxTren, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Pesanan Masuk Sistem',
                        data: [4, 7, 5, 12, 9, 15],
                        borderColor: '#60a5fa',
                        backgroundColor: 'rgba(96, 165, 250, 0.2)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });

        // --- 🕹️ LOGIC SPA MULTI TAB NAVIGATION ---
        function showTab(tabName) {
            document.querySelectorAll('.tab-content').forEach(function(content) {
                content.classList.add('hidden');
            });

            var targetTab = document.getElementById('tab-' + tabName);
            if(targetTab) {
                targetTab.classList.remove('hidden');
            }

            const btns = {
                'beranda': document.getElementById('btn-beranda'),
                'keranjang': document.getElementById('btn-keranjang'),
                'pesanan': document.getElementById('btn-pesanan'),
                'editProfile': document.getElementById('btn-edit-profile')
            };

            if(btns.beranda) btns.beranda.className = "w-full bouncing block text-sm font-cute text-center text-pink-600 bg-pink-50 hover:bg-gradient-to-r hover:from-[#d14d72] hover:to-pink-400 hover:text-white p-4 rounded-2xl transition border-2 border-dashed border-pink-400 shadow-sm";
            if(btns.keranjang) btns.keranjang.className = "w-full bouncing block text-sm font-cute text-center text-orange-600 bg-orange-50 hover:bg-orange-400 hover:text-white p-4 rounded-2xl transition border-2 border-dashed border-orange-400 shadow-sm";
            if(btns.pesanan) btns.pesanan.className = "w-full bouncing block text-sm font-cute text-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white p-4 rounded-2xl transition border-2 border-dashed border-blue-400 shadow-sm";
            if(btns.editProfile) btns.editProfile.className = "w-full bouncing block text-sm font-bold text-center text-gray-500 hover:text-pink-500 hover:bg-yellow-50 p-3 rounded-2xl transition border border-gray-200";

            if (tabName === 'beranda' && btns.beranda) {
                btns.beranda.className = "w-full text-sm font-cute text-center text-white bg-gradient-to-r from-[#d14d72] to-pink-400 p-4 rounded-2xl shadow-md transition border-b-4 border-[#b03d5d]";
            } else if (tabName === 'keranjang' && btns.keranjang) {
                btns.keranjang.className = "w-full text-sm font-cute text-center text-white bg-gradient-to-r from-orange-400 to-amber-400 p-4 rounded-2xl shadow-md transition border-b-4 border-orange-600";
            } else if (tabName === 'pesanan' && btns.pesanan) {
                btns.pesanan.className = "w-full text-sm font-cute text-center text-white bg-gradient-to-r from-blue-500 to-indigo-400 p-4 rounded-2xl shadow-md transition border-b-4 border-blue-700";
            } else if (tabName === 'edit-profile' && btns.editProfile) {
                btns.editProfile.className = "w-full text-sm font-cute text-center text-white bg-gradient-to-r from-yellow-500 to-amber-400 p-4 rounded-2xl shadow-md transition border-b-4 border-yellow-600";
            }
        }
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🎀 JALIN - Marketplace Pengantin Imut 🎀</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Quicksand:wght@500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Quicksand', sans-serif;
            background: radial-gradient(circle, #fff0f3 0%, #ffe5ec 100%);
        }
        .font-cute { font-family: 'Fredoka One', cursive; }
        .bouncing:hover { transform: scale(1.05) rotate(-1deg); transition: 0.3s; }
    </style>
</head>
<body class="text-gray-700 text-left pb-12 selection:bg-pink-200">

    <nav class="bg-white/80 backdrop-blur-md py-4 shadow-md sticky top-0 z-50 border-b-4 border-dashed border-pink-200">
        <div class="w-[90%] mx-auto flex justify-between items-center">
            <div class="text-3xl font-cute text-[#d14d72] tracking-wider cursor-pointer transform hover:scale-110 transition" onclick="window.location.href='/'">
                ✨JALIN<span class="text-yellow-400">.</span>✨
            </div>
            <div class="flex items-center gap-6 text-sm font-bold text-[#d14d72]">
                <a href="/" class="hover:text-pink-400 transition bg-pink-50 px-3 py-1.5 rounded-xl border border-pink-100">🏠 Beranda</a>

                <a href="{{ route('customer.keranjang') }}" class="relative bouncing bg-orange-50 hover:bg-orange-100 p-2.5 rounded-full border border-orange-200 transition text-xl flex items-center justify-center shadow-sm" title="Lihat Keranjang Belanja">
                    🛒
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-cute px-1.5 py-0.5 rounded-full animate-pulse">3</span>
                </a>

                <div class="flex items-center gap-2 bg-gradient-to-r from-[#ffb7c5] to-[#d14d72] px-5 py-2.5 rounded-2xl text-white shadow-lg font-cute tracking-wide">
                    👑 <span>{{ strtoupper($user->nama_lengkap) }}</span> 👑
                </div>
            </div>
        </div>
    </nav>

    <main class="w-[90%] mx-auto py-10">
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
                        <div class="text-[10px] font-cute text-pink-400 uppercase tracking-[0.2em] mb-2 ml-2">🧁 Menu Utama</div>

                        <a href="{{ route('customer.dashboard') }}" class="block text-sm font-cute text-center text-white bg-gradient-to-r from-[#d14d72] to-pink-400 p-4 rounded-2xl shadow-md transform hover:translate-y-1 transition border-b-4 border-[#b03d5d]">
                            ✨ Katalog Belanja
                        </a>

                        <a href="{{ route('customer.pesanan') }}" class="bouncing block text-sm font-cute text-center text-blue-600 bg-blue-50 hover:bg-blue-600 hover:text-white p-4 rounded-2xl transition border-2 border-dashed border-blue-400 flex items-center justify-center gap-2 shadow-sm">
                            📦 Pesanan Saya
                        </a>

                        <a href="{{ route('chat.index') }}" class="bouncing block text-sm font-cute text-center text-[#d14d72] bg-pink-50 hover:bg-[#d14d72] hover:text-white p-4 rounded-2xl transition border-2 border-dashed border-[#d14d72] flex items-center justify-center gap-2 shadow-sm">
                            <span>💬</span> Ruang Chat Diskusi
                        </a>

                        <a href="/customer/edit-profile" class="bouncing block text-sm font-bold text-center text-gray-500 hover:text-pink-500 hover:bg-yellow-50 p-3 rounded-2xl transition border border-gray-200">
                            ⚙️ Edit Profil Kamu
                        </a>

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

                <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-pink-100 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-left w-full">
                        <h1 class="text-3xl font-cute text-gray-800">Halo, Kak <span class="italic text-[#d14d72]">{{ $user->nama_lengkap }}</span>! 👋🎈</h1>
                        <p class="text-pink-500 text-sm font-bold mt-1">✨ Hari bahagia pernikahan impianmu sedang kami racik dengan cinta! ✨</p>
                    </div>
                    <div class="text-center bg-gradient-to-b from-yellow-50 to-yellow-100 px-6 py-5 rounded-[2rem] border-4 border-dashed border-yellow-400 min-w-[120px] shadow-md transform rotate-3 hover:rotate-0 transition">
                        <div class="text-4xl font-cute text-[#d14d72]">{{ $countBooking }}</div>
                        <div class="text-[10px] text-yellow-700 uppercase font-cute tracking-wider mt-1">🎉 Bookings</div>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-orange-100 text-left relative">
                    <div class="absolute -top-4 left-6 bg-orange-400 text-white font-cute text-xs px-4 py-1.5 rounded-full shadow-md">🛍️ REKOMENDASI KATALOG JALIN</div>
                    <h3 class="font-cute text-gray-400 tracking-widest uppercase text-xs mb-6 mt-2">🛍️ Belanja Kebutuhan Nikah</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                        @forelse($katalogLayanan as $layanan)
                        <div class="bg-white rounded-3xl border-2 border-orange-50 overflow-hidden shadow-sm hover:shadow-xl transition transform hover:-translate-y-1 flex flex-col justify-between">

                            <div class="w-full h-44 bg-pink-50 relative overflow-hidden">
                                @if(!empty($layanan->gambar))
                                    <img src="{{ asset('uploads/' . $layanan->gambar) }}"
                                         class="w-full h-full object-cover"
                                         onerror="this.onerror=null; this.src='https://placehold.co/400x300/fff0f3/d14d72?text=JALIN+Vendor+Item';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl bg-orange-50 text-orange-300">📦</div>
                                @endif
                                <span class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm text-[9px] font-cute text-orange-600 px-2.5 py-1 rounded-full shadow-sm border border-orange-100">⭐ 4.9</span>
                            </div>

                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div class="space-y-1">
                                    <p class="text-[10px] text-pink-500 font-cute tracking-wide uppercase">🏪 {{ $layanan->nama_toko ?? 'Mitra JALIN' }}</p>
                                    <h4 class="font-bold text-sm text-gray-800 line-clamp-2 min-h-10 leading-tight">{{ $layanan->nama_layanan }}</h4>
                                </div>

                                <div class="mt-4 pt-3 border-t border-dashed border-gray-100 flex items-center justify-between gap-2">
                                    <div>
                                        <p class="text-[8px] text-gray-400 font-bold uppercase tracking-wider">Harga Paket</p>
                                        <p class="text-sm font-cute text-[#d14d72]">Rp {{ number_format($layanan->harga ?? 0, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="flex items-center gap-1.5">
                                        <form action="{{ route('customer.customer.keranjang.add') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="id_layanan" value="{{ $layanan->id_layanan }}">
                                            <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 text-white p-2 rounded-xl text-xs font-bold shadow-sm transition active:scale-95" title="Masukkan Keranjang">
                                                🛒+
                                            </button>
                                        </form>

                                        <form action="{{ route('chat.mulai', $layanan->id_vendor) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="bg-orange-400 hover:bg-orange-500 text-white text-[10px] font-cute px-3 py-2 rounded-xl shadow-sm transition active:scale-95 flex items-center gap-1">
                                                <span>💬</span> Chat
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-span-full py-10 text-center text-gray-400 text-xs italic">Belum ada item katalog terdaftar hari ini, Kak. 🌴</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-blue-100 text-left relative">
                    <div class="absolute -top-4 left-6 bg-blue-400 text-white font-cute text-xs px-4 py-1.5 rounded-full shadow-md">5 RIWAYAT ORDER TERAKHIR</div>
                    <h3 class="font-cute text-gray-400 tracking-widest uppercase text-xs mb-6 mt-2">🧁 Pesanan Terbaru</h3>
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
                                @if(count($queryOrders) > 0)
                                    @foreach($queryOrders as $row)
                                    <tr class="border-b border-gray-100 last:border-0 hover:bg-blue-50/30 transition">
                                        <td class="py-5 pl-2 font-cute text-gray-700 text-xs text-blue-600">{{ $row->nama_vendor }}</td>
                                        <td class="py-5 text-gray-500 text-xs font-bold">{{ $row->nama_layanan }}</td>
                                        <td class="py-5 text-center">
                                            <span class="bg-blue-100 text-blue-600 px-4 py-2 rounded-full text-[9px] font-cute tracking-wider border border-blue-200">
                                                💖 {{ $row->status }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="3" class="py-12 text-center text-gray-400 text-xs italic">Belum ada pesanan terbaru nih, Kak. Yuk belanja di atas! 🛒</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[3rem] shadow-xl border-4 border-purple-100 text-left relative">
                    <div class="absolute -top-4 left-6 bg-purple-400 text-white font-cute text-xs px-4 py-1.5 rounded-full shadow-md">HISTORI NOMINAL</div>
                    <h3 class="font-cute text-gray-400 tracking-widest uppercase text-xs mb-6 mt-2">💸 Riwayat Pembayaran</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="text-[11px] font-cute text-purple-400 uppercase tracking-[0.2em] border-b-2 border-dashed border-gray-100">
                                    <th class="pb-4 pl-2">Layanan</th>
                                    <th class="pb-4">Bukti Nota</th>
                                    <th class="pb-4">Metode</th>
                                    <th class="pb-4">Nominal</th>
                                    <th class="pb-4 text-right">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm font-medium">
                                @if(count($queryPayment) > 0)
                                    @foreach($queryPayment as $pay)
                                    <tr class="border-b border-gray-100 last:border-0 hover:bg-purple-50/30 transition">
                                        <td class="py-5 pl-2 font-cute text-gray-700 text-xs">{{ $pay->nama_layanan }}</td>
                                        <td class="py-5">
                                            @if(!empty($pay->bukti_bayar) && file_exists(public_path('uploads/' . $pay->bukti_bayar)))
                                                <a href="{{ asset('uploads/' . $pay->bukti_bayar) }}" target="_blank">
                                                    <img src="{{ asset('uploads/' . $pay->bukti_bayar) }}" class="w-12 h-12 object-cover rounded-2xl border-2 border-dashed border-purple-300 hover:scale-110 transition shadow-md">
                                                </a>
                                            @else
                                                <span class="text-[9px] text-gray-300 italic uppercase">Kosong Kelinci 🐰</span>
                                            @endif
                                        </td>
                                        <td class="py-5 text-gray-400 text-xs font-bold uppercase">{{ $pay->metode_bayar }}</td>
                                        <td class="py-5 text-[#d14d72] font-cute text-xs">Rp {{ number_format($pay->jumlah_bayar, 0, ',', '.') }}</td>
                                        <td class="py-5 text-right">
                                            <span class="px-3 py-1.5 rounded-full text-[9px] font-cute {{ $pay->status_pembayaran == 'Terkonfirmasi' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-orange-100 text-orange-700 border border-orange-200' }}">
                                                🍭 {{ $pay->status_pembayaran }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5" class="py-12 text-center text-gray-400 text-xs italic">Belum ada riwayat pembayaran.</td></tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>

            </section>
        </div>
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | JALIN</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fffafb; }
        .sidebar-link-active { background-color: #fff0f3; color: #d14d72; font-weight: bold; border-right: 4px solid #d14d72; }
        .content-section { display: none; }
        .content-section.active { display: block; }

        .section-enter {
            animation: sectionFadeIn 0.35s cubic-bezier(0.23, 1, 0.32, 1) forwards;
        }

        @keyframes sectionFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .badge-menunggu { background-color: #fef3c7; color: #92400e; }
        .badge-terverifikasi { background-color: #d1fae5; color: #065f46; }
        .badge-ditolak { background-color: #fee2e2; color: #991b1b; }

        .btn-verifikasi {
            transition: transform 160ms cubic-bezier(0.23, 1, 0.32, 1);
        }
        .btn-verifikasi:active {
            transform: scale(0.97);
        }

        .card-hover {
            transition: box-shadow 200ms cubic-bezier(0.23, 1, 0.32, 1), transform 200ms cubic-bezier(0.23, 1, 0.32, 1);
        }
        @media (hover: hover) and (pointer: fine) {
            .card-hover:hover {
                box-shadow: 0 8px 25px -5px rgba(209, 77, 114, 0.1);
                transform: translateY(-2px);
            }
        }
    </style>
</head>
<body class="text-gray-800">

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-72 bg-white border-r border-pink-50 hidden md:block sticky top-0 h-screen shadow-sm z-50">
            <div class="p-8">
                <div class="text-3xl font-bold text-[#d14d72] mb-12 cursor-pointer" onclick="window.location.href='/'">JALIN<span class="text-[#ffb7c5]">.</span></div>
                <nav class="space-y-3">
                    <div id="link-dashboard" onclick="switchSection('dashboard', this)" class="sidebar-link sidebar-link-active flex items-center gap-4 p-4 text-sm cursor-pointer rounded-xl transition-all">
                        <span>📊</span> Dashboard
                    </div>
                    <div id="link-vendor" onclick="switchSection('vendor', this)" class="sidebar-link flex items-center gap-4 p-4 text-sm text-gray-500 hover:bg-pink-50 cursor-pointer rounded-xl transition-all">
                        <span>🏪</span> Verifikasi Vendor
                        @if($vendorMenunggu > 0)
                            <span class="ml-auto bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $vendorMenunggu }}</span>
                        @endif
                    </div>
                    <div id="link-layanan" onclick="switchSection('layanan', this)" class="sidebar-link flex items-center gap-4 p-4 text-sm text-gray-500 hover:bg-pink-50 cursor-pointer rounded-xl transition-all">
                        <span>🛍️</span> Verifikasi Layanan
                        @if($layananMenunggu > 0)
                            <span class="ml-auto bg-red-100 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $layananMenunggu }}</span>
                        @endif
                    </div>

                    <div class="pt-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-4">Pengaturan</p>
                        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Keluar dari aplikasi?')">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-4 p-4 text-sm text-red-400 font-bold rounded-xl transition text-left hover:bg-red-50">
                                <span>🚪</span> Keluar
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>

        <!-- Mobile Header -->
        <div class="md:hidden fixed top-0 left-0 right-0 bg-white border-b border-pink-50 p-4 z-50 flex items-center justify-between">
            <div class="text-xl font-bold text-[#d14d72]">JALIN<span class="text-[#ffb7c5]">.</span></div>
            <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden')" class="text-gray-600 p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
        <div id="mobile-menu" class="hidden md:hidden fixed top-14 left-0 right-0 bg-white border-b border-pink-50 p-4 z-40 shadow-lg">
            <div onclick="switchSection('dashboard', document.getElementById('link-dashboard')); document.getElementById('mobile-menu').classList.add('hidden')" class="p-3 text-sm cursor-pointer hover:bg-pink-50 rounded-lg">📊 Dashboard</div>
            <div onclick="switchSection('vendor', document.getElementById('link-vendor')); document.getElementById('mobile-menu').classList.add('hidden')" class="p-3 text-sm cursor-pointer hover:bg-pink-50 rounded-lg">🏪 Verifikasi Vendor</div>
            <div onclick="switchSection('layanan', document.getElementById('link-layanan')); document.getElementById('mobile-menu').classList.add('hidden')" class="p-3 text-sm cursor-pointer hover:bg-pink-50 rounded-lg">🛍️ Verifikasi Layanan</div>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-6 pt-20 md:p-10 lg:p-16 md:pt-10">
            <header class="flex justify-between items-center mb-12">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Halo, Admin! 👋</h1>
                    <p class="text-[10px] text-pink-400 font-bold uppercase tracking-widest mt-1 italic">PANEL ADMINISTRATOR JALIN</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-full border-2 border-white shadow-md overflow-hidden">
                    <img src="{{ $user->foto_profil ? asset('uploads/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name=Admin&background=d14d72&color=fff' }}" class="w-full h-full object-cover" alt="Admin">
                </div>
            </header>

            @if(session('sukses'))
                <div class="bg-green-50 text-green-600 p-4 rounded-2xl mb-6 text-xs font-bold border border-green-100">
                    {{ session('sukses') }}
                </div>
            @endif

            <!-- Section: Dashboard Overview -->
            <section id="section-dashboard" class="content-section active section-enter">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <div class="bg-white p-6 rounded-3xl border border-pink-50 shadow-sm card-hover">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Vendor</p>
                        <h3 class="text-2xl font-bold mt-2 text-[#d14d72]">{{ $totalVendor }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-pink-50 shadow-sm card-hover">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Customer</p>
                        <h3 class="text-2xl font-bold mt-2 text-gray-800">{{ $totalCustomer }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-pink-50 shadow-sm card-hover">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Total Layanan</p>
                        <h3 class="text-2xl font-bold mt-2 text-gray-800">{{ $totalLayanan }}</h3>
                    </div>
                    <div class="bg-white p-6 rounded-3xl border border-pink-50 shadow-sm card-hover">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Menunggu Review</p>
                        <h3 class="text-2xl font-bold mt-2 text-amber-500">{{ $vendorMenunggu + $layananMenunggu }}</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <div class="bg-white p-8 rounded-3xl border border-pink-50 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Vendor Menunggu Verifikasi</h3>
                        @if($vendorMenunggu > 0)
                            <div class="space-y-3">
                                @foreach($vendors->where('status_verifikasi', 'menunggu')->take(5) as $v)
                                    <div class="flex items-center justify-between p-3 bg-amber-50/50 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-pink-100 rounded-full overflow-hidden">
                                                <img src="{{ $v->foto_profil ? asset('uploads/'.$v->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($v->nama_lengkap).'&background=d14d72&color=fff&size=36' }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">{{ $v->nama_toko ?? $v->nama_lengkap }}</p>
                                                <p class="text-[10px] text-gray-400">{{ $v->email }}</p>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold badge-menunggu px-2 py-1 rounded-full">Menunggu</span>
                                    </div>
                                @endforeach
                            </div>
                            <button onclick="switchSection('vendor', document.getElementById('link-vendor'))" class="mt-4 text-xs text-[#d14d72] font-semibold hover:underline">Lihat semua →</button>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada vendor yang menunggu verifikasi.</p>
                        @endif
                    </div>

                    <div class="bg-white p-8 rounded-3xl border border-pink-50 shadow-sm">
                        <h3 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6">Layanan Menunggu Verifikasi</h3>
                        @if($layananMenunggu > 0)
                            <div class="space-y-3">
                                @foreach($layananList->where('status_verifikasi', 'menunggu')->take(5) as $l)
                                    <div class="flex items-center justify-between p-3 bg-amber-50/50 rounded-xl">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 bg-pink-100 rounded-lg overflow-hidden">
                                                <img src="{{ $l->gambar ? asset('uploads/'.$l->gambar) : 'https://ui-avatars.com/api/?name=L&background=ffb7c5&color=fff&size=36' }}" class="w-full h-full object-cover">
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-700">{{ $l->nama_layanan }}</p>
                                                <p class="text-[10px] text-gray-400">{{ $l->nama_toko ?? $l->nama_lengkap }}</p>
                                            </div>
                                        </div>
                                        <span class="text-[10px] font-bold badge-menunggu px-2 py-1 rounded-full">Menunggu</span>
                                    </div>
                                @endforeach
                            </div>
                            <button onclick="switchSection('layanan', document.getElementById('link-layanan'))" class="mt-4 text-xs text-[#d14d72] font-semibold hover:underline">Lihat semua →</button>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada layanan yang menunggu verifikasi.</p>
                        @endif
                    </div>
                </div>
            </section>

            <!-- Section: Verifikasi Vendor -->
            <section id="section-vendor" class="content-section">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800">Verifikasi Vendor</h2>
                    <p class="text-sm text-gray-400 mt-1">Kelola dan verifikasi akun vendor JALIN Partner</p>
                </div>

                <div class="space-y-4">
                    @forelse($vendors as $vendor)
                        <div class="bg-white p-6 rounded-2xl border border-pink-50 shadow-sm card-hover">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-pink-100 rounded-full overflow-hidden shrink-0">
                                        <img src="{{ $vendor->foto_profil ? asset('uploads/'.$vendor->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($vendor->nama_lengkap).'&background=d14d72&color=fff&size=56' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $vendor->nama_toko ?? $vendor->nama_lengkap }}</h4>
                                        <p class="text-xs text-gray-400">{{ $vendor->email }}</p>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $vendor->no_whatsapp ?? '-' }}</span>
                                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">{{ $vendor->alamat ?? 'Alamat belum diisi' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-bold px-3 py-1 rounded-full badge-{{ $vendor->status_verifikasi }}">
                                        {{ ucfirst($vendor->status_verifikasi) }}
                                    </span>

                                    @if($vendor->status_verifikasi === 'menunggu')
                                        <div class="flex gap-2">
                                            <form action="{{ url('/admin/vendor/verifikasi/'.$vendor->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="terverifikasi">
                                                <button type="submit" class="btn-verifikasi bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-xl">
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ url('/admin/vendor/verifikasi/'.$vendor->id) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="ditolak">
                                                <button type="submit" class="btn-verifikasi bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-4 py-2 rounded-xl">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($vendor->status_verifikasi === 'ditolak')
                                        <form action="{{ url('/admin/vendor/verifikasi/'.$vendor->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="terverifikasi">
                                            <button type="submit" class="btn-verifikasi bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-xl">
                                                Terima Ulang
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-10 rounded-2xl border border-pink-50 text-center">
                            <p class="text-gray-400 text-sm italic">Belum ada vendor terdaftar.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- Section: Verifikasi Layanan -->
            <section id="section-layanan" class="content-section">
                <div class="mb-8">
                    <h2 class="text-xl font-bold text-gray-800">Verifikasi Layanan</h2>
                    <p class="text-sm text-gray-400 mt-1">Review dan verifikasi layanan yang diajukan vendor</p>
                </div>

                <div class="space-y-4">
                    @forelse($layananList as $layanan)
                        <div class="bg-white p-6 rounded-2xl border border-pink-50 shadow-sm card-hover">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-16 h-16 bg-pink-50 rounded-xl overflow-hidden shrink-0">
                                        <img src="{{ $layanan->gambar ? asset('uploads/'.$layanan->gambar) : 'https://ui-avatars.com/api/?name='.urlencode(substr($layanan->nama_layanan,0,1)).'&background=ffb7c5&color=fff&size=64' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $layanan->nama_layanan }}</h4>
                                        <p class="text-xs text-gray-400">Vendor: {{ $layanan->nama_toko ?? $layanan->nama_lengkap }}</p>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="text-[10px] bg-pink-50 text-[#d14d72] font-semibold px-2 py-0.5 rounded-full">Rp {{ number_format($layanan->harga, 0, ',', '.') }}</span>
                                            <span class="text-[10px] bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">DP Min: {{ $layanan->minimal_dp_persen }}%</span>
                                        </div>
                                        @if($layanan->deskripsi)
                                            <p class="text-xs text-gray-400 mt-2 line-clamp-2">{{ $layanan->deskripsi }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="text-[10px] font-bold px-3 py-1 rounded-full badge-{{ $layanan->status_verifikasi }}">
                                        {{ ucfirst($layanan->status_verifikasi) }}
                                    </span>

                                    @if($layanan->status_verifikasi === 'menunggu')
                                        <div class="flex gap-2">
                                            <form action="{{ url('/admin/layanan/verifikasi/'.$layanan->id_layanan) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="terverifikasi">
                                                <button type="submit" class="btn-verifikasi bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-xl">
                                                    Terima
                                                </button>
                                            </form>
                                            <form action="{{ url('/admin/layanan/verifikasi/'.$layanan->id_layanan) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="ditolak">
                                                <button type="submit" class="btn-verifikasi bg-red-500 hover:bg-red-600 text-white text-xs font-semibold px-4 py-2 rounded-xl">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    @elseif($layanan->status_verifikasi === 'ditolak')
                                        <form action="{{ url('/admin/layanan/verifikasi/'.$layanan->id_layanan) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="terverifikasi">
                                            <button type="submit" class="btn-verifikasi bg-green-500 hover:bg-green-600 text-white text-xs font-semibold px-4 py-2 rounded-xl">
                                                Terima Ulang
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white p-10 rounded-2xl border border-pink-50 text-center">
                            <p class="text-gray-400 text-sm italic">Belum ada layanan yang diajukan.</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </main>
    </div>

    <script>
        function switchSection(sectionId, clickedLink) {
            document.querySelectorAll('.content-section').forEach(s => {
                s.classList.remove('active', 'section-enter');
            });
            document.querySelectorAll('.sidebar-link').forEach(l => {
                l.classList.remove('sidebar-link-active');
                l.classList.add('text-gray-500');
            });

            const section = document.getElementById('section-' + sectionId);
            section.classList.add('active');
            void section.offsetWidth;
            section.classList.add('section-enter');

            if (clickedLink) {
                clickedLink.classList.add('sidebar-link-active');
                clickedLink.classList.remove('text-gray-500');
            }
        }

        // Handle tab from URL parameter
        document.addEventListener('DOMContentLoaded', function() {
            const params = new URLSearchParams(window.location.search);
            const tab = params.get('tab');
            if (tab) {
                const link = document.getElementById('link-' + tab);
                if (link) switchSection(tab, link);
            }
        });
    </script>
</body>
</html>

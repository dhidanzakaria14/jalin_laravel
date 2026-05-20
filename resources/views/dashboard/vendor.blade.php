<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard | JALIN Partner</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fffafb; }
        .sidebar-link-active { background-color: #fff0f3; color: #d14d72; font-weight: bold; border-right: 4px solid #d14d72; }
        .content-section { display: none; }
        .content-section.active { display: block; animation: fadeIn 0.4s ease forwards; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="text-gray-800 text-left">

    <div class="flex min-h-screen">
        <aside class="w-72 bg-white border-r border-pink-50 hidden md:block sticky top-0 h-screen shadow-sm z-50">
            <div class="p-8 text-left">
    <div class="text-3xl font-bold text-[#d14d72] mb-12 cursor-pointer" onclick="window.location.href='/'">JALIN<span class="text-[#ffb7c5]">.</span></div>
    <nav class="space-y-4">
        <div id="link-dashboard" onclick="switchSection('dashboard', this)" class="sidebar-link sidebar-link-active flex items-center gap-4 p-4 text-sm cursor-pointer transition-all"><span>📊</span> Dashboard</div>
        <div id="link-layanan" onclick="switchSection('layanan', this)" class="sidebar-link flex items-center gap-4 p-4 text-sm text-gray-500 hover:bg-pink-50 cursor-pointer transition-all"><span>🛍️</span> Kelola Layanan</div>
        <div id="link-kategori" onclick="switchSection('kategori', this)" class="sidebar-link flex items-center gap-4 p-4 text-sm text-gray-500 hover:bg-pink-50 cursor-pointer transition-all"><span>📂</span> Kelola Kategori</div>

        <div onclick="window.location.href='{{ route('chat.index') }}'" class="sidebar-link flex items-center gap-4 p-4 text-sm text-gray-500 hover:bg-pink-50 cursor-pointer transition-all">
            <span>💬</span> Kotak Masuk Chat
        </div>

        <div id="link-pesanan" onclick="switchSection('pesanan', this)" class="sidebar-link flex items-center gap-4 p-4 text-sm text-gray-500 hover:bg-pink-50 cursor-pointer transition-all"><span>📩</span> Pesanan Masuk</div>

                    <div class="pt-10">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 ml-4">Pengaturan</p>
                        <a href="#" id="link-profile" onclick="switchSection('profile', this)" class="sidebar-link flex items-center gap-4 p-4 text-sm text-gray-500 hover:bg-pink-50 cursor-pointer rounded-2xl transition"><span>🏠</span> Edit Profil</a>

                        <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Keluar dari aplikasi?')">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-4 p-4 text-sm text-red-400 font-bold rounded-2xl transition text-left">
                                <span>🚪</span> Keluar
                            </button>
                        </form>
                    </div>
                </nav>
            </div>
        </aside>

        <main class="flex-1 p-10 lg:p-16">
            <header class="flex justify-between items-center mb-12 text-left">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 text-left">Halo, {{ explode(' ', $user->nama_lengkap)[0] }}! 👋</h1>
                    <p class="text-[10px] text-pink-400 font-bold uppercase tracking-widest mt-1 italic">{{ strtoupper($user->nama_toko ?? 'Partner Jalin') }}</p>
                </div>
                <div class="w-12 h-12 bg-pink-100 rounded-full border-2 border-white shadow-md overflow-hidden cursor-pointer" onclick="document.getElementById('link-profile').click()">
                    <img src="{{ $user->foto_profil ? asset('uploads/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama_lengkap) }}" class="w-full h-full object-cover">
                </div>
            </header>

            @if(session('sukses'))
                <div class="bg-green-50 text-green-600 p-4 rounded-2xl mb-6 text-xs font-bold border border-green-100">
                    🎉 {{ session('sukses') }}
                </div>
            @endif

            <section id="section-dashboard" class="content-section active text-left">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    <div class="bg-white p-8 rounded-4xl border border-pink-50 shadow-sm"><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Layanan</p><h3 class="text-2xl font-bold mt-2 text-[#d14d72]">{{ $totalLayanan }}</h3></div>
                    <div class="bg-white p-8 rounded-4xl border border-pink-50 shadow-sm"><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pesanan</p><h3 class="text-2xl font-bold mt-2">{{ $totalPesanan }}</h3></div>
                    <div class="bg-white p-8 rounded-4xl border border-pink-50 shadow-sm"><p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider text-left">Impact Score</p><h3 class="text-2xl font-bold mt-2 italic text-left">850 pts</h3></div>
                </div>
                <div class="bg-white p-10 rounded-6xl shadow-xl border border-pink-50 text-left">
                    <h3 class="font-bold text-gray-800 mb-8 flex items-center gap-2 uppercase text-xs tracking-widest text-left">Tren Penjualan</h3>
                    <div class="h-[350px]"><canvas id="vendorChart"></canvas></div>
                </div>
            </section>

            <section id="section-layanan" class="content-section text-left">
                <div class="flex justify-between items-center mb-10 text-left">
                    <div class="text-left">
                        <h2 class="text-2xl font-bold text-gray-800 italic uppercase">Katalog Layanan</h2>
                        <p class="text-sm text-gray-400 mt-1">Kelola foto dan harga jasa Anda.</p>
                    </div>
                    <button onclick="openModal('modalTambah')" class="bg-[#d14d72] text-white px-8 py-4 rounded-2xl text-[10px] font-bold uppercase shadow-lg hover:bg-[#b03d5d] transition">+ Tambah Layanan</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-left">
                    @forelse($layananQuery as $l)
                    @php $id_lay = $l->id_layanan ?? $l->id; @endphp
                    <div class="bg-white p-8 rounded-6xl shadow-sm border border-pink-50 flex gap-8 animate-fadeIn text-left">
                        <img src="{{ $l->gambar && $l->gambar != 'default-layanan.png' ? asset('uploads/'.$l->gambar) : 'https://placehold.co/150x150?text=Layanan' }}" class="w-32 h-32 rounded-4xl object-cover bg-pink-50 shadow-inner">
                        <div class="flex-1 text-left">
                            <p class="text-[9px] font-bold text-pink-400 uppercase tracking-widest text-left">{{ $l->nama_kategori ?? 'General' }}</p>
                            <h4 class="font-bold text-lg text-gray-800 text-left">{{ $l->nama_layanan }}</h4>
                            <p class="text-[#d14d72] font-bold mt-1 text-sm text-left">Rp {{ number_format($l->harga, 0, ',', '.') }}</p>

                            <p class="text-xs text-gray-400 mt-1">
                                Minimal DP: <span class="font-bold text-gray-700">{{ $l->minimal_dp_persen ?? 0 }}%</span>
                                <span class="text-pink-400 font-semibold">(Rp {{ number_format(($l->harga * ($l->minimal_dp_persen ?? 0)) / 100, 0, ',', '.') }})</span>
                            </p>

                            <div class="mt-6 flex gap-2 items-center">
                                <button onclick='openEditModal({{ json_encode($l) }})' class="text-[9px] bg-blue-50 text-blue-500 px-4 py-2 rounded-xl font-bold uppercase hover:bg-blue-500 hover:text-white transition">Edit</button>

                                <form action="{{ route('vendor.layanan.delete', $id_lay) }}" method="POST" onsubmit="return confirm('Hapus item layanan ini dari katalog?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-[9px] bg-red-50 text-red-400 px-4 py-2 rounded-xl font-bold uppercase hover:bg-red-500 hover:text-white transition">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-1 md:col-span-2 bg-white rounded-3xl border border-pink-50 p-12 text-center text-gray-400 italic text-sm">
                        Belum ada layanan yang ditambahkan ke katalog.
                    </div>
                    @endforelse
                </div>
            </section>

            <section id="section-kategori" class="content-section text-left">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800 italic uppercase">Manajemen Kategori Jasa</h2>
                    <p class="text-sm text-gray-400 mt-1">Tambahkan opsi kategori baru untuk mempermudah klasifikasi katalog tokomu.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    <div class="bg-white p-8 rounded-4xl border border-pink-50 shadow-sm">
                        <h4 class="font-bold text-sm text-gray-700 uppercase tracking-wider mb-4">Tambah Kategori Baru</h4>
                        <form action="{{ route('vendor.kategori.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <input type="text" name="nama_kategori" required placeholder="Contoh: Dekorasi Rustic, Catering" class="w-full px-5 py-3.5 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-pink-100 text-sm bg-gray-50/50">
                            </div>
                            <button type="submit" class="w-full bg-[#d14d72] text-white py-3.5 rounded-xl font-bold uppercase text-xs shadow-md hover:bg-[#b03d5d] transition">
                                Simpan Kategori
                            </button>
                        </form>
                    </div>

                    <div class="lg:col-span-2 bg-white rounded-4xl border border-pink-50 shadow-sm overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-pink-50/50 border-b border-pink-50 text-xs font-bold uppercase text-gray-400 tracking-wider">
                                    <th class="px-6 py-4 w-16 text-center">No</th>
                                    <th class="px-6 py-4">Nama Kategori</th>
                                    <th class="px-6 py-4 w-40 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                @if(!empty($listKategori))
                                    @foreach($listKategori as $index => $k)
                                    @php
                                        $id_kat = $k['id_kategori'] ?? $k['id'];
                                        $nama_kat = $k['nama_kategori'] ?? $k['nama'] ?? '';
                                    @endphp
                                    <tr class="hover:bg-pink-50/20 transition">
                                        <td class="px-6 py-4 text-center font-medium text-gray-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $nama_kat }}</td>
                                        <td class="px-6 py-4 text-center flex justify-center gap-2 items-center">
                                            <button onclick="openEditKategoriModal('{{ $id_kat }}', '{{ $nama_kat }}')" class="text-xs font-bold uppercase text-blue-500 bg-blue-50 hover:bg-blue-500 hover:text-white px-3 py-1 rounded-lg transition">
                                                Edit
                                            </button>

                                            <form action="{{ route('vendor.kategori.delete', $id_kat) }}" method="POST" onsubmit="return confirm('Hapus kategori ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-xs font-bold uppercase text-red-400 hover:text-white bg-red-50 hover:bg-red-400 px-3 py-1 rounded-lg transition">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="3" class="px-6 py-10 text-center text-gray-400 italic">Belum ada kategori yang terdaftar.</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="section-pesanan" class="content-section text-left">
                <h2 class="text-2xl font-bold mb-10 text-gray-800 uppercase tracking-widest text-left">Antrean Proyek</h2>
                <div class="bg-white rounded-[2.5rem] border border-pink-50 overflow-hidden shadow-xl text-center p-20 text-gray-400 italic text-sm">Belum ada pesanan masuk.</div>
            </section>

            <section id="section-profile" class="content-section text-left">
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Pengaturan Profil JALIN Partner 🛠️</h2>
                    <p class="text-sm text-gray-400 mt-1">Kelola data informasi identitas dan toko Anda.</p>
                </div>

                <div class="bg-white p-10 rounded-[2.5rem] border border-pink-50 shadow-sm max-w-3xl">
                    <form action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="flex items-center gap-6 pb-6 border-b border-gray-50">
                            <div class="w-20 h-20 rounded-full overflow-hidden border-2 border-pink-100 bg-pink-50 shadow-inner">
                                <img src="{{ $user->foto_profil ? asset('uploads/'.$user->foto_profil) : 'https://ui-avatars.com/api/?name='.urlencode($user->nama_lengkap) }}" class="w-full h-full object-cover">
                            </div>
                            <div class="space-y-1">
                                <label class="text-xs font-bold text-[#d14d72] uppercase tracking-wider block">Ganti Foto Profil</label>
                                <input type="file" name="foto_profil" class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-[#d14d72] hover:file:bg-pink-100 cursor-pointer">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1">Nama Pemilik</label>
                            <input type="text" name="nama_lengkap" required value="{{ old('nama_lengkap', $user->nama_lengkap) }}" class="w-full px-5 py-3.5 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-pink-100 text-sm bg-gray-50/50">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1">Nama Toko / Brand Vendor</label>
                            <input type="text" name="nama_toko" value="{{ old('nama_toko', $user->nama_toko) }}" placeholder="Contoh: Maju Dekorasi Sidoarjo" class="w-full px-5 py-3.5 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-pink-100 text-sm bg-gray-50/50">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1">No. WhatsApp</label>
                            <input type="text" name="no_wa" required value="{{ old('no_wa', $user->no_whatsapp) }}" class="w-full px-5 py-3.5 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-pink-100 text-sm bg-gray-50/50">
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider ml-1">Alamat Kantor/Studio</label>
                            <textarea name="alamat" rows="3" placeholder="Tuliskan alamat lengkap studio vendor Anda..." class="w-full px-5 py-3.5 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-pink-100 text-sm resize-none bg-gray-50/50">{{ old('alamat', $user->alamat) }}</textarea>
                        </div>

                        <div class="pt-6 border-t border-gray-100 mt-8 flex items-center justify-between gap-4">
                            <div>
                                <button type="submit" class="bg-[#d14d72] text-white px-8 py-4 rounded-xl text-xs font-bold uppercase shadow-lg hover:bg-[#b03d5d] transition transform active:scale-95">
                                    Simpan Perubahan
                                </button>
                            </div>
                    </form>
                            <div>
                                <form action="{{ route('vendor.profile.delete') }}" method="POST" onsubmit="return confirm('⚠️ PERINGATAN MUTLAK: Apakah Anda yakin ingin menghapus akun JALIN Partner Anda secara permanen?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold uppercase text-red-500 hover:text-white border border-red-200 hover:bg-red-500 px-6 py-4 rounded-xl transition transform active:scale-95">
                                        ❌ Hapus Akun Permanen
                                    </button>
                                </form>
                            </div>
                        </div>
                </div>
            </section>
        </main>
    </div>

    <div id="modalTambah" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm text-left">
        <div class="bg-white w-full max-w-lg rounded-[3.5rem] p-12 shadow-2xl overflow-y-auto max-h-[90vh] text-left">
            <h3 class="text-2xl font-bold italic mb-8">Tambah Katalog</h3>
            <form action="{{ route('vendor.layanan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 text-left">
                @csrf
                <select name="id_kategori" required class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm">
                    <option value="">Pilih Kategori</option>
                    @if(!empty($listKategori))
                        @foreach($listKategori as $k)
                            <option value="{{ $k['id_kategori'] ?? $k['id'] ?? '' }}">
                                {{ $k['nama_kategori'] ?? $k['nama'] ?? 'Kategori' }}
                            </option>
                        @endforeach
                    @endif
                </select>
                <input type="file" name="foto" required class="w-full text-xs text-gray-400">
                <input type="text" id="add_nama_layanan" name="nama_layanan" required placeholder="Nama Layanan" class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm">

                <input type="number" id="add_harga" name="harga" oninput="hitungDPTambah()" required placeholder="Harga" class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm">

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase ml-1">Minimal DP (%)</label>
                    <input type="number" id="add_dp_percent" name="minimal_dp_percent" oninput="hitungDPTambah()" min="0" max="100" placeholder="Contoh: 20" class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm">
                    <p class="text-xs text-pink-500 font-semibold mt-1 ml-1" id="label_dp_tambah">Nominal DP: Rp 0</p>
                </div>

                <textarea name="deskripsi" rows="3" placeholder="Deskripsi" class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm resize-none"></textarea>
                <button type="submit" class="w-full bg-[#d14d72] text-white py-5 rounded-3xl font-bold uppercase text-[10px] shadow-lg">Simpan Katalog</button>
                <button type="button" onclick="closeModal('modalTambah')" class="w-full text-gray-400 text-[10px] font-bold uppercase mt-2">Batal</button>
            </form>
        </div>
    </div>

    <div id="modalEditKategori" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm text-left">
        <div class="bg-white w-full max-w-md rounded-[3rem] p-10 shadow-2xl text-left">
            <h3 class="text-xl font-bold italic mb-6">Ubah Nama Kategori</h3>
            <form id="formEditKategori" action="#" method="POST" class="space-y-5">
                @csrf
                @method('PUT')
                <div>
                    <label class="text-[11px] font-bold text-gray-400 uppercase tracking-wider block mb-2 ml-1">Nama Kategori</label>
                    <input type="text" id="edit_nama_kategori" name="nama_kategori" required class="w-full px-5 py-3.5 rounded-xl border border-gray-100 outline-none focus:ring-2 focus:ring-pink-100 text-sm bg-gray-50/50">
                </div>
                <button type="submit" class="w-full bg-blue-500 text-white py-4 rounded-2xl font-bold uppercase text-xs shadow-md hover:bg-blue-600 transition">Simpan Perubahan</button>
                <button type="button" onclick="closeModal('modalEditKategori')" class="w-full text-gray-400 text-xs font-bold uppercase mt-2">Batal</button>
            </form>
        </div>
    </div>

    <div id="modalEditLayanan" class="fixed inset-0 z-50 hidden items-center justify-center p-4 bg-black/50 backdrop-blur-sm text-left">
        <div class="bg-white w-full max-w-lg rounded-[3.5rem] p-12 shadow-2xl overflow-y-auto max-h-[90vh] text-left">
            <h3 class="text-2xl font-bold italic mb-8">Edit Detail Katalog</h3>

            <form id="formEditLayanan" action="#" method="POST" enctype="multipart/form-data" class="space-y-5 text-left">
                @csrf

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Nama Layanan</label>
                    <input type="text" id="edit_nama_layanan" name="nama_layanan" required class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm">
                </div>
                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Harga Jasa (Rp)</label>
                    <input type="number" id="edit_harga_layanan" name="harga" oninput="hitungDPEdit()" required class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm">
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Minimal DP (%)</label>
                    <input type="number" id="edit_dp_layanan" name="minimal_dp_percent" oninput="hitungDPEdit()" min="0" max="100" class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm">
                    <p class="text-xs text-pink-500 font-semibold mt-1" id="label_dp_edit">Nominal DP: Rp 0</p>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-gray-400 uppercase">Deskripsi</label>
                    <textarea id="edit_deskripsi_layanan" name="deskripsi" rows="3" class="w-full px-6 py-4 rounded-2xl border bg-gray-50 outline-none text-sm resize-none"></textarea>
                </div>

                <div class="space-y-1">
                    <label class="text-xs font-bold text-[#d14d72] uppercase block">Ganti Foto Jasa (Opsional)</label>
                    <input type="file" name="foto" class="text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-[#d14d72] hover:file:bg-pink-100 cursor-pointer">
                    <p class="text-[10px] text-gray-400 mt-1 italic">*Biarkan kosong jika tidak ingin mengubah foto katalog lama.</p>
                </div>

                <button type="submit" class="w-full bg-[#d14d72] text-white py-5 rounded-3xl font-bold uppercase text-[10px] shadow-lg">Simpan Perubahan</button>
                <button type="button" onclick="closeModal('modalEditLayanan')" class="w-full text-gray-400 text-[10px] font-bold uppercase mt-2">Batal</button>
            </form>
        </div>
    </div>

    <script>
        function switchSection(sectionId, element) {
            document.querySelectorAll('.content-section').forEach(s => s.classList.remove('active'));
            document.getElementById('section-' + sectionId).classList.add('active');
            document.querySelectorAll('.sidebar-link').forEach(l => l.classList.remove('sidebar-link-active', 'text-[#d14d72]', 'font-bold'));
            if(element) element.classList.add('sidebar-link-active', 'text-[#d14d72]', 'font-bold');
        }

        function openModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }

        function openEditKategoriModal(id, namaKategori) {
            const form = document.getElementById('formEditKategori');
            form.action = `/vendor/kategori/update/${id}`;
            document.getElementById('edit_nama_kategori').value = namaKategori;
            openModal('modalEditKategori');
        }

        // REVISI LIVE TEXT CALCULATOR JAVASCRIPT
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(angka);
        }

        function hitungDPTambah() {
            const harga = parseFloat(document.getElementById('add_harga').value) || 0;
            const persen = parseFloat(document.getElementById('add_dp_percent').value) || 0;
            const nominal = (harga * persen) / 100;
            document.getElementById('label_dp_tambah').innerText = "Nominal DP: " + formatRupiah(nominal);
        }

        function hitungDPEdit() {
            const harga = parseFloat(document.getElementById('edit_harga_layanan').value) || 0;
            const persen = parseFloat(document.getElementById('edit_dp_layanan').value) || 0;
            const nominal = (harga * persen) / 100;
            document.getElementById('label_dp_edit').innerText = "Nominal DP: " + formatRupiah(nominal);
        }

        function openEditModal(layanan) {
            const form = document.getElementById('formEditLayanan');
            form.action = `/vendor/layanan/update/${layanan.id_layanan || layanan.id}`;

            document.getElementById('edit_nama_layanan').value = layanan.nama_layanan;
            document.getElementById('edit_harga_layanan').value = layanan.harga;
            document.getElementById('edit_deskripsi_layanan').value = layanan.deskripsi || '';

            // FIX PETAKAN DATA DB: Ambil kolom minimal_dp_persen dari database asli
            const dpPersen = layanan.minimal_dp_persen || 0;
            document.getElementById('edit_dp_layanan').value = dpPersen;

            // Trigger kalkulasi nominal langsung saat modal terbuka
            const nominal = (layanan.harga * dpPersen) / 100;
            document.getElementById('label_dp_edit').innerText = "Nominal DP: " + formatRupiah(nominal);

            openModal('modalEditLayanan');
        }

        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('tab') === 'kategori') {
                switchSection('kategori', document.getElementById('link-kategori'));
            } else if (urlParams.get('tab') === 'profile') {
                switchSection('profile', document.getElementById('link-profile'));
            } else if (urlParams.get('tab') === 'layanan') {
                switchSection('layanan', document.getElementById('link-layanan'));
            }

            const ctx = document.getElementById('vendorChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Sales',
                        data: [5, 8, 4, 12, 10, {{ $totalPesanan }}],
                        borderColor: '#d14d72',
                        backgroundColor: 'rgba(209, 77, 114, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
            });
        };
    </script>
</body>
</html>

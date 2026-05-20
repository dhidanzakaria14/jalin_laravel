<!DOCTYPE html>
<html lang="id">
<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Poppins', sans-serif; }
        .fancy { font-family: 'Playfair Display', serif; }
    </style>
</head>

<body class="bg-gradient-to-br from-rose-50 via-pink-50 to-white min-h-screen">

<!-- HEADER -->
<header class="text-center py-12">
    <h1 class="fancy text-6xl text-rose-500 drop-shadow-sm">JALIN Wedding</h1>
    <p class="text-slate-500 mt-2 tracking-wide">
        Curating timeless wedding experiences ✨
    </p>
</header>

<div class="max-w-6xl mx-auto px-6 pb-16">

    <!-- FORM CARD -->
    <div class="bg-white/80 backdrop-blur-xl border border-rose-100 shadow-2xl rounded-3xl p-8 mb-12">

        <h2 class="fancy text-2xl text-rose-600 mb-6">Tambah Layanan Vendor 💍</h2>

        <form action="/layanan" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @csrf

            <input type="number" name="id_vendor" placeholder="ID Vendor"
                class="p-3 rounded-2xl border border-rose-100 focus:ring-2 focus:ring-rose-300 outline-none">

            <input type="number" name="id_kategori" placeholder="Kategori Wedding"
                class="p-3 rounded-2xl border border-rose-100 focus:ring-2 focus:ring-rose-300 outline-none">

            <input type="text" name="nama_layanan" placeholder="Nama Layanan (e.g Wedding Decoration)"
                class="col-span-2 p-3 rounded-2xl border border-rose-100 focus:ring-2 focus:ring-rose-300 outline-none">

            <input type="number" name="harga" placeholder="Harga Paket"
                class="p-3 rounded-2xl border border-rose-100 focus:ring-2 focus:ring-rose-300 outline-none">

            <input type="number" name="minimal_dp_persen" placeholder="DP (%)"
                class="p-3 rounded-2xl border border-rose-100 focus:ring-2 focus:ring-rose-300 outline-none">

            <textarea name="deskripsi" placeholder="Deskripsi layanan wedding..."
                class="col-span-2 p-3 rounded-2xl border border-rose-100 focus:ring-2 focus:ring-rose-300 outline-none"></textarea>

            <button class="col-span-2 bg-gradient-to-r from-rose-400 to-pink-500 text-white py-3 rounded-2xl font-semibold shadow-lg hover:scale-[1.02] transition">
                + Simpan Vendor Wedding
            </button>
        </form>
    </div>

    <!-- TABLE CARD -->
    <div class="bg-white/80 backdrop-blur-xl border border-rose-100 shadow-xl rounded-3xl overflow-hidden">

        <div class="p-6 border-b border-rose-100">
            <h2 class="fancy text-2xl text-rose-600">Daftar Layanan Wedding 💐</h2>
        </div>

        <table class="w-full">
            <thead class="bg-rose-50 text-rose-700">
                <tr>
                    <th class="p-5 text-left">Layanan</th>
                    <th class="p-5 text-left">Harga</th>
                    <th class="p-5 text-center">Aksi</th>
                </tr>
            </thead>

            <tbody class="divide-y divide-rose-100">

            @foreach($data as $item)
                <tr class="hover:bg-rose-50 transition">

                    <td class="p-5">
                        <div class="font-semibold text-slate-700">
                            {{ $item->nama_layanan }}
                        </div>
                        <div class="text-sm text-slate-400">
                            Vendor Wedding Service
                        </div>
                    </td>

                    <td class="p-5 text-rose-500 font-semibold">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </td>

                    <td class="p-5 text-center space-x-3">

                        <button class="text-blue-500 hover:text-blue-700 font-medium">
                            Edit
                        </button>

                        <form action="/layanan/{{ $item->id_layanan }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')

                            <button class="text-red-400 hover:text-red-600 font-medium">
                                Hapus
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>

    </div>

</div>

</body>
</html>

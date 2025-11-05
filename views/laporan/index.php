<?php include __DIR__ . '/../layouts/partials/header.php'; ?>
<!-- Header Halaman -->
<div class="mb-6"><h1 class="text-2xl font-bold text-gray-800">Modul Laporan</h1><p class="text-gray-600">Pilih jenis laporan yang ingin Anda lihat atau cetak.</p></div>
<!-- Grid Kartu Laporan -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Kartu Laporan Kunjungan -->
    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col"><div class="flex-shrink-0"><div class="w-12 h-12 flex items-center justify-center bg-indigo-100 text-indigo-600 rounded-lg"><i data-lucide="calendar-week" class="h-6 w-6"></i></div></div><div class="flex-grow mt-4"><h3 class="text-lg font-semibold text-gray-800">Laporan Kunjungan</h3><p class="text-gray-600 mt-2 text-sm">Rekapitulasi semua kunjungan pasien (ANC, KB, Imunisasi) berdasarkan periode tanggal.</p></div><div class="mt-4"><a href="/laporan/kunjungan" class="w-full text-center inline-flex items-center justify-center px-4 py-2 bg-indigo-600 text-white font-semibold text-sm rounded-lg hover:bg-indigo-700">Buka Laporan</a></div></div>
    
    <!-- KARTU BARU: Laporan Kohort Bayi -->
    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col"><div class="flex-shrink-0"><div class="w-12 h-12 flex items-center justify-center bg-pink-100 text-pink-600 rounded-lg"><i data-lucide="clipboard-heart" class="h-6 w-6"></i></div></div><div class="flex-grow mt-4"><h3 class="text-lg font-semibold text-gray-800">Laporan Kohort Bayi</h3><p class="text-gray-600 mt-2 text-sm">Laporan pemantauan angka kematian bayi (AKB) dan angka kematian neonatal (AKN) per tahun.</p></div><div class="mt-4"><a href="/laporan/kohort" class="w-full text-center inline-flex items-center justify-center px-4 py-2 bg-pink-600 text-white font-semibold text-sm rounded-lg hover:bg-pink-700">Buka Laporan</a></div></div>

    <!-- KARTU BARU: Laporan PWS KIA -->
    <div class="bg-white shadow-md rounded-lg p-6 flex flex-col"><div class="flex-shrink-0"><div class="w-12 h-12 flex items-center justify-center bg-green-100 text-green-600 rounded-lg"><i data-lucide="activity" class="h-6 w-6"></i></div></div><div class="flex-grow mt-4"><h3 class="text-lg font-semibold text-gray-800">Laporan PWS KIA</h3><p class="text-gray-600 mt-2 text-sm">Pemantauan Wilayah Setempat Kesehatan Ibu dan Anak sesuai standar Kemenkes RI.</p></div><div class="mt-4"><a href="/laporan/pws_kia" class="w-full text-center inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg hover:bg-green-700">Buka Laporan</a></div></div>
</div>
<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>

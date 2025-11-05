<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-6"><h1 class="text-2xl font-bold text-gray-800">Formulir Pencatatan Imunisasi</h1><a href="/imunisasi" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300"><i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali</a></div>

<div class="bg-white shadow-md rounded-lg max-w-4xl mx-auto">
    <form action="/imunisasi/store" method="POST" class="p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Data Imunisasi -->
            <div class="md:col-span-2"><label for="bayi_id" class="block text-sm font-medium text-gray-700">Pilih Bayi <span class="text-red-500">*</span></label><select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="bayi_id" name="bayi_id" required><option value="">-- Pilih Bayi (Nama Ibu) --</option><?php foreach ($bayis as $bayi): ?><option value="<?php echo e($bayi['id']); ?>"><?php echo e($bayi['nama_bayi']); ?> (Ibu: <?php echo e($bayi['nama_ibu']); ?>)</option><?php endforeach; ?></select></div>
            <div><label for="tanggal_imunisasi" class="block text-sm font-medium text-gray-700">Tanggal Imunisasi <span class="text-red-500">*</span></label><input type="date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="tanggal_imunisasi" name="tanggal_imunisasi" value="<?php echo date('Y-m-d'); ?>" required></div>
            <div><label for="pemberi_imunisasi" class="block text-sm font-medium text-gray-700">Diberikan Oleh</label><input type="text" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100" id="pemberi_imunisasi" name="pemberi_imunisasi" value="<?php echo e($_SESSION['user_name']); ?>" readonly></div>
            <div class="md:col-span-2">
                <label for="jenis_imunisasi" class="block text-sm font-medium text-gray-700">Jenis Imunisasi <span class="text-red-500">*</span></label>
                <select class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" id="jenis_imunisasi" name="jenis_imunisasi" required>
                    <option value="">-- Pilih Jenis Imunisasi --</option>
                    
                    <!-- Imunisasi Dasar (0-11 Bulan) -->
                    <optgroup label="=== IMUNISASI DASAR (0-11 Bulan) ===">
                        <option value="Hepatitis B (HB-0)">Hepatitis B (HB-0) - Usia 0-24 jam</option>
                        <option value="BCG">BCG - Usia 1 bulan</option>
                        <option value="Polio 1 (OPV 1)">Polio 1 (OPV 1) - Usia 1 bulan</option>
                        <option value="DPT-HB-Hib 1">DPT-HB-Hib 1 (Pentavalen 1) - Usia 2 bulan</option>
                        <option value="Polio 2 (OPV 2)">Polio 2 (OPV 2) - Usia 2 bulan</option>
                        <option value="DPT-HB-Hib 2">DPT-HB-Hib 2 (Pentavalen 2) - Usia 3 bulan</option>
                        <option value="Polio 3 (OPV 3)">Polio 3 (OPV 3) - Usia 3 bulan</option>
                        <option value="DPT-HB-Hib 3">DPT-HB-Hib 3 (Pentavalen 3) - Usia 4 bulan</option>
                        <option value="Polio 4 (OPV 4)">Polio 4 (OPV 4) - Usia 4 bulan</option>
                        <option value="IPV (Polio Suntik)">IPV (Polio Suntik) - Usia 4 bulan</option>
                        <option value="Campak/MR 1">Campak/MR 1 - Usia 9 bulan</option>
                    </optgroup>
                    
                    <!-- Imunisasi Lanjutan -->
                    <optgroup label="=== IMUNISASI LANJUTAN ===">
                        <option value="DPT-HB-Hib Lanjutan">DPT-HB-Hib Lanjutan - Usia 18 bulan</option>
                        <option value="Campak/MR 2">Campak/MR 2 - Usia 18 bulan</option>
                    </optgroup>
                    
                    <!-- Imunisasi Anak Usia Sekolah (BIAS) -->
                    <optgroup label="=== IMUNISASI ANAK SEKOLAH (BIAS) ===">
                        <option value="Campak/MR 3">Campak/MR 3 - Kelas 1 SD</option>
                        <option value="DT (Difteri Tetanus)">DT (Difteri Tetanus) - Kelas 1 SD</option>
                        <option value="Td (Tetanus Difteri)">Td (Tetanus Difteri) - Kelas 2 SD</option>
                        <option value="Td Lanjutan">Td Lanjutan - Kelas 5 SD</option>
                    </optgroup>
                    
                    <!-- Imunisasi Tambahan/Pilihan -->
                    <optgroup label="=== IMUNISASI TAMBAHAN (Opsional) ===">
                        <option value="PCV (Pneumokokus)">PCV (Pneumokokus)</option>
                        <option value="Rotavirus">Rotavirus</option>
                        <option value="Influenza">Influenza</option>
                        <option value="Varicella (Cacar Air)">Varicella (Cacar Air)</option>
                        <option value="MMR (Campak, Gondongan, Rubella)">MMR (Campak, Gondongan, Rubella)</option>
                        <option value="Hepatitis A">Hepatitis A</option>
                        <option value="Tifoid">Tifoid</option>
                        <option value="Japanese Encephalitis">Japanese Encephalitis</option>
                        <option value="Dengue">Dengue (Demam Berdarah)</option>
                        <option value="HPV (Human Papillomavirus)">HPV (Human Papillomavirus)</option>
                    </optgroup>
                    
                    <!-- Imunisasi Khusus -->
                    <optgroup label="=== IMUNISASI KHUSUS ===">
                        <option value="Rabies">Rabies (Pasca Gigitan Hewan)</option>
                        <option value="Meningitis">Meningitis</option>
                        <option value="Yellow Fever">Yellow Fever (Demam Kuning)</option>
                    </optgroup>
                </select>
                <p class="mt-1 text-xs text-gray-500">Sesuai Peraturan Menteri Kesehatan RI tentang Program Imunisasi Nasional</p>
            </div>
            <!-- DATA PERTUMBUHAN (BARU) -->
            <div class="md:col-span-2"><hr><h4 class="text-md font-semibold text-gray-800 mt-4">Data Pertumbuhan Bayi</h4></div>
            <div><label for="berat_badan" class="block text-sm font-medium text-gray-700">Berat Badan (Kg)</label><input type="text" id="berat_badan" name="berat_badan" placeholder="cth: 8.5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label for="panjang_badan" class="block text-sm font-medium text-gray-700">Panjang Badan (Cm)</label><input type="text" id="panjang_badan" name="panjang_badan" placeholder="cth: 70.2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label for="lingkar_kepala" class="block text-sm font-medium text-gray-700">Lingkar Kepala (Cm)</label><input type="text" id="lingkar_kepala" name="lingkar_kepala" placeholder="cth: 45.5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            
            <div class="md:col-span-2"><label for="catatan" class="block text-sm font-medium text-gray-700">Catatan (No. Batch Vaksin, dll)</label><textarea class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" id="catatan" name="catatan" rows="3"></textarea></div>
        </div>
        <div class="border-t mt-6 pt-6"><button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Simpan Catatan Imunisasi</button></div>
    </form>
</div>
<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>

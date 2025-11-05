<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<!-- Header Halaman -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Pemeriksaan Nifas</h1>
    <a href="/nifas" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<div class="bg-white shadow-md rounded-lg max-w-4xl mx-auto">
    <form action="/nifas/store" method="POST" class="p-6">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <!-- Data Kunjungan -->
            <div class="md:col-span-2"><label for="pasien_id" class="block text-sm font-medium text-gray-700">Pilih Pasien (Ibu) <span class="text-red-500">*</span></label><select id="pasien_id" name="pasien_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="">-- Cari Pasien --</option><?php foreach ($pasiens as $pasien): ?><option value="<?php echo e($pasien['id']); ?>"><?php echo e($pasien['no_rm']); ?> - <?php echo e($pasien['nama_pasien']); ?></option><?php endforeach; ?></select></div>
            <div><label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700">Tgl Kunjungan <span class="text-red-500">*</span></label><input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label for="hari_ke" class="block text-sm font-medium text-gray-700">Kunjungan Hari Ke- <span class="text-red-500">*</span></label><input type="number" id="hari_ke" name="hari_ke" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>

            <!-- Patient History Cards -->
            <div class="md:col-span-4" id="patient-info-cards" style="display: none;">
                <hr class="my-4">
                <h4 class="text-md font-semibold text-gray-800 mb-4">Riwayat Kesehatan Pasien</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Basic Info Card -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h5 class="font-semibold text-blue-800 mb-2">Informasi Dasar</h5>
                        <div class="space-y-1 text-sm">
                            <p><strong>Nama:</strong> <span id="card-nama"></span></p>
                            <p><strong>No. RM:</strong> <span id="card-no-rm"></span></p>
                            <p><strong>Tanggal Lahir:</strong> <span id="card-tanggal-lahir"></span></p>
                            <p><strong>Alamat:</strong> <span id="card-alamat"></span></p>
                            <p><strong>Telepon:</strong> <span id="card-telepon"></span></p>
                        </div>
                    </div>

                    <!-- Pregnancy Status Card -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h5 class="font-semibold text-green-800 mb-2">Status Kehamilan</h5>
                        <div class="space-y-1 text-sm">
                            <p><strong>GPA:</strong> <span id="card-gpa"></span></p>
                            <p><strong>HPHT:</strong> <span id="card-hpht"></span></p>
                            <p><strong>Nama Suami:</strong> <span id="card-suami"></span></p>
                            <p><strong>Gol. Darah:</strong> <span id="card-gol-darah"></span></p>
                        </div>
                    </div>

                    <!-- Medical History Card -->
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <h5 class="font-semibold text-red-800 mb-2">Riwayat Kesehatan</h5>
                        <div class="space-y-1 text-sm">
                            <p><strong>Riwayat Penyakit:</strong> <span id="card-riwayat-penyakit"></span></p>
                            <p><strong>Hipertensi:</strong> <span id="card-hipertensi"></span></p>
                            <p><strong>Diabetes:</strong> <span id="card-diabetes"></span></p>
                            <p><strong>Alergi Obat:</strong> <span id="card-alergi"></span></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tanda Vital -->
            <div class="md:col-span-4"><hr><h4 class="text-md font-semibold text-gray-800 mt-4">Tanda-tanda Vital</h4></div>
            <div><label class="block text-sm font-medium text-gray-700">Tekanan Darah</label><input type="text" name="tekanan_darah" placeholder="cth: 120/80" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700">Suhu (°C)</label><input type="text" name="suhu" placeholder="cth: 36.5" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700">Nadi (x/menit)</label><input type="number" name="nadi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div><label class="block text-sm font-medium text-gray-700">Pernapasan (x/menit)</label><input type="number" name="pernapasan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            
            <!-- Pemeriksaan Fisik -->
            <div class="md:col-span-4"><hr><h4 class="text-md font-semibold text-gray-800 mt-4">Pemeriksaan Fisik</h4></div>
            <div class="md:col-span-2"><label for="tfu" class="block text-sm font-medium text-gray-700">Tinggi Fundus Uteri (TFU)</label><input type="text" id="tfu" name="tfu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div class="md:col-span-2"><label for="lokia" class="block text-sm font-medium text-gray-700">Lokia</label><input type="text" id="lokia" name="lokia" placeholder="Rubra, Serosa, Alba..." class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
            <div class="md:col-span-4"><label for="kondisi_perineum" class="block text-sm font-medium text-gray-700">Kondisi Perineum / Luka Jahitan</label><textarea id="kondisi_perineum" name="kondisi_perineum" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea></div>
            
             <!-- Anamnesis & Planning -->
            <div class="md:col-span-4"><hr><h4 class="text-md font-semibold text-gray-800 mt-4">Anamnesis & Planning</h4></div>
            <div class="md:col-span-2"><label for="keluhan" class="block text-sm font-medium text-gray-700">Keluhan Ibu</label><textarea id="keluhan" name="keluhan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea></div>
            <div class="md:col-span-2"><label for="tindakan_konseling" class="block text-sm font-medium text-gray-700">Tindakan & Konseling</label><textarea id="tindakan_konseling" name="tindakan_konseling" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea></div>
        </div>
        <div class="border-t mt-6 pt-6"><button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">Simpan Kunjungan</button></div>
    </form>
</div>

<script>
document.getElementById('pasien_id').addEventListener('change', function() {
    const pasienId = this.value;
    if (pasienId) {
        fetch(`/nifas/get_patient_data?pasien_id=${pasienId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show cards
                    document.getElementById('patient-info-cards').style.display = 'block';

                    // Populate cards
                    document.getElementById('card-nama').textContent = data.nama_pasien || '-';
                    document.getElementById('card-no-rm').textContent = data.no_rm || '-';
                    document.getElementById('card-tanggal-lahir').textContent = data.tanggal_lahir || '-';
                    document.getElementById('card-alamat').textContent = data.alamat_lengkap || '-';
                    document.getElementById('card-telepon').textContent = data.no_telepon || '-';
                    document.getElementById('card-gpa').textContent = data.gravida_paritas || '-';
                    document.getElementById('card-hpht').textContent = data.hpht || '-';
                    document.getElementById('card-suami').textContent = data.nama_suami || '-';
                    document.getElementById('card-gol-darah').textContent = data.golongan_darah || '-';
                    document.getElementById('card-riwayat-penyakit').textContent = data.riwayat_penyakit || '-';
                    document.getElementById('card-hipertensi').textContent = data.hipertensi || '-';
                    document.getElementById('card-diabetes').textContent = data.diabetes || '-';
                    document.getElementById('card-alergi').textContent = data.alergi_obat || '-';

                    // Auto-fill hari_ke (postpartum days)
                    if (data.hari_ke !== null && data.hari_ke > 0) {
                        document.getElementById('hari_ke').value = data.hari_ke;
                    } else {
                        document.getElementById('hari_ke').value = '';
                    }
                } else {
                    alert('Gagal memuat data pasien: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data pasien');
            });
    } else {
        // Hide cards if no patient selected
        document.getElementById('patient-info-cards').style.display = 'none';
        document.getElementById('hari_ke').value = '';
    }
});
</script>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>

<?php
// File: views/pasien/create.php atau edit.php (Final Disempurnakan)
$is_edit = isset($pasien);
$form_action = $is_edit ? "/pasien/update" : "/pasien/store";
$page_title = $is_edit ? "Edit Data Pasien" : "Tambah Pasien Baru";
$button_text = $is_edit ? "Update Data Pasien" : "Simpan Data Pasien";
include __DIR__ . '/../layouts/partials/header.php';
?>
<!-- Header Halaman -->
<div class="flex justify-between items-center mb-6"><h1 class="text-2xl font-bold text-gray-800"><?php echo $page_title; ?></h1><a href="/pasien" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300"><i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali</a></div>
<!-- Form -->
<div class="bg-white shadow-md rounded-lg"><form action="<?php echo $form_action; ?>" method="POST" class="p-6">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>"><?php if ($is_edit): ?><input type="hidden" name="id" value="<?php echo e($pasien['id']); ?>"><?php endif; ?>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div><label for="no_rm" class="block text-sm font-medium text-gray-700">No. RM <span class="text-red-500">*</span></label><input type="text" id="no_rm" name="no_rm" value="<?php echo e($pasien['no_rm'] ?? ''); ?>" required readonly class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100"></div>
        <div><label for="nama_pasien" class="block text-sm font-medium text-gray-700">Nama Pasien <span class="text-red-500">*</span></label><input type="text" id="nama_pasien" name="nama_pasien" value="<?php echo e($pasien['nama_pasien'] ?? ''); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        <div><label for="nik" class="block text-sm font-medium text-gray-700">NIK</label><input type="text" id="nik" name="nik" value="<?php echo e($pasien['nik'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        <div><label for="tempat_lahir" class="block text-sm font-medium text-gray-700">Tempat Lahir</label><input type="text" id="tempat_lahir" name="tempat_lahir" value="<?php echo e($pasien['tempat_lahir'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        <div><label for="tanggal_lahir" class="block text-sm font-medium text-gray-700">Tgl Lahir <span class="text-red-500">*</span></label><input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo e($pasien['tanggal_lahir'] ?? ''); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        <div><label for="golongan_darah" class="block text-sm font-medium text-gray-700">Gol. Darah</label><select id="golongan_darah" name="golongan_darah" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="">Pilih...</option><option value="A" <?php echo (($pasien['golongan_darah'] ?? '') == 'A') ? 'selected' : ''; ?>>A</option><option value="B" <?php echo (($pasien['golongan_darah'] ?? '') == 'B') ? 'selected' : ''; ?>>B</option><option value="AB" <?php echo (($pasien['golongan_darah'] ?? '') == 'AB') ? 'selected' : ''; ?>>AB</option><option value="O" <?php echo (($pasien['golongan_darah'] ?? '') == 'O') ? 'selected' : ''; ?>>O</option></select></div>
        <div class="md:col-span-2"><label for="alamat_lengkap" class="block text-sm font-medium text-gray-700">Alamat <span class="text-red-500">*</span></label><textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e($pasien['alamat_lengkap'] ?? ''); ?></textarea></div>
        <div><label for="no_telepon" class="block text-sm font-medium text-gray-700">No. Telepon</label><input type="text" id="no_telepon" name="no_telepon" value="<?php echo e($pasien['no_telepon'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        <div>
            <label for="pendidikan_terakhir" class="block text-sm font-medium text-gray-700">Pendidikan</label>
            <select id="pendidikan_terakhir" name="pendidikan_terakhir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Pilih...</option>
                <option value="Tidak Sekolah" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'Tidak Sekolah') ? 'selected' : ''; ?>>Tidak Sekolah</option>
                <option value="SD" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'SD') ? 'selected' : ''; ?>>SD (Sekolah Dasar)</option>
                <option value="SMP" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'SMP') ? 'selected' : ''; ?>>SMP (Sekolah Menengah Pertama)</option>
                <option value="SMA" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'SMA') ? 'selected' : ''; ?>>SMA (Sekolah Menengah Atas)</option>
                <option value="SMK" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'SMK') ? 'selected' : ''; ?>>SMK (Sekolah Menengah Kejuruan)</option>
                <option value="D1" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'D1') ? 'selected' : ''; ?>>D1 (Diploma 1)</option>
                <option value="D2" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'D2') ? 'selected' : ''; ?>>D2 (Diploma 2)</option>
                <option value="D3" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'D3') ? 'selected' : ''; ?>>D3 (Diploma 3)</option>
                <option value="D4" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'D4') ? 'selected' : ''; ?>>D4 (Diploma 4)</option>
                <option value="S1" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'S1') ? 'selected' : ''; ?>>S1 (Sarjana)</option>
                <option value="S2" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'S2') ? 'selected' : ''; ?>>S2 (Magister)</option>
                <option value="S3" <?php echo (($pasien['pendidikan_terakhir'] ?? '') == 'S3') ? 'selected' : ''; ?>>S3 (Doktor)</option>
            </select>
        </div>
        <div>
            <label for="pekerjaan" class="block text-sm font-medium text-gray-700">Pekerjaan Pasien</label>
            <select id="pekerjaan" name="pekerjaan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Pilih...</option>
                <option value="Tidak Bekerja" <?php echo (($pasien['pekerjaan'] ?? '') == 'Tidak Bekerja') ? 'selected' : ''; ?>>Tidak Bekerja</option>
                <option value="Pegawai Negeri Sipil" <?php echo (($pasien['pekerjaan'] ?? '') == 'Pegawai Negeri Sipil') ? 'selected' : ''; ?>>Pegawai Negeri Sipil (PNS)</option>
                <option value="Pegawai Swasta" <?php echo (($pasien['pekerjaan'] ?? '') == 'Pegawai Swasta') ? 'selected' : ''; ?>>Pegawai Swasta</option>
                <option value="Wiraswasta" <?php echo (($pasien['pekerjaan'] ?? '') == 'Wiraswasta') ? 'selected' : ''; ?>>Wiraswasta</option>
                <option value="Petani" <?php echo (($pasien['pekerjaan'] ?? '') == 'Petani') ? 'selected' : ''; ?>>Petani</option>
                <option value="Nelayan" <?php echo (($pasien['pekerjaan'] ?? '') == 'Nelayan') ? 'selected' : ''; ?>>Nelayan</option>
                <option value="Buruh" <?php echo (($pasien['pekerjaan'] ?? '') == 'Buruh') ? 'selected' : ''; ?>>Buruh</option>
                <option value="Pedagang" <?php echo (($pasien['pekerjaan'] ?? '') == 'Pedagang') ? 'selected' : ''; ?>>Pedagang</option>
                <option value="Guru/Dosen" <?php echo (($pasien['pekerjaan'] ?? '') == 'Guru/Dosen') ? 'selected' : ''; ?>>Guru/Dosen</option>
                <option value="Dokter" <?php echo (($pasien['pekerjaan'] ?? '') == 'Dokter') ? 'selected' : ''; ?>>Dokter</option>
                <option value="Perawat" <?php echo (($pasien['pekerjaan'] ?? '') == 'Perawat') ? 'selected' : ''; ?>>Perawat</option>
                <option value="Polisi" <?php echo (($pasien['pekerjaan'] ?? '') == 'Polisi') ? 'selected' : ''; ?>>Polisi</option>
                <option value="TNI" <?php echo (($pasien['pekerjaan'] ?? '') == 'TNI') ? 'selected' : ''; ?>>TNI</option>
                <option value="Ibu Rumah Tangga" <?php echo (($pasien['pekerjaan'] ?? '') == 'Ibu Rumah Tangga') ? 'selected' : ''; ?>>Ibu Rumah Tangga</option>
                <option value="Mahasiswa/Pelajar" <?php echo (($pasien['pekerjaan'] ?? '') == 'Mahasiswa/Pelajar') ? 'selected' : ''; ?>>Mahasiswa/Pelajar</option>
                <option value="Pensiunan" <?php echo (($pasien['pekerjaan'] ?? '') == 'Pensiunan') ? 'selected' : ''; ?>>Pensiunan</option>
            </select>
        </div>
        
        <!-- Kolom Agama (BARU) -->
        <div>
            <label for="agama" class="block text-sm font-medium text-gray-700">Agama</label>
            <select id="agama" name="agama" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Pilih...</option>
                <option value="Islam" <?php echo (($pasien['agama'] ?? '') == 'Islam') ? 'selected' : ''; ?>>Islam</option>
                <option value="Kristen Protestan" <?php echo (($pasien['agama'] ?? '') == 'Kristen Protestan') ? 'selected' : ''; ?>>Kristen Protestan</option>
                <option value="Kristen Katolik" <?php echo (($pasien['agama'] ?? '') == 'Kristen Katolik') ? 'selected' : ''; ?>>Kristen Katolik</option>
                <option value="Hindu" <?php echo (($pasien['agama'] ?? '') == 'Hindu') ? 'selected' : ''; ?>>Hindu</option>
                <option value="Buddha" <?php echo (($pasien['agama'] ?? '') == 'Buddha') ? 'selected' : ''; ?>>Buddha</option>
                <option value="Konghucu" <?php echo (($pasien['agama'] ?? '') == 'Konghucu') ? 'selected' : ''; ?>>Konghucu</option>
            </select>
        </div>

        <!-- Kolom Status Pernikahan (BARU) -->
        <div>
            <label for="status_pernikahan" class="block text-sm font-medium text-gray-700">Status Pernikahan</label>
            <select id="status_pernikahan" name="status_pernikahan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Pilih...</option>
                <option value="Menikah" <?php echo (($pasien['status_pernikahan'] ?? '') == 'Menikah') ? 'selected' : ''; ?>>Menikah</option>
                <option value="Belum Menikah" <?php echo (($pasien['status_pernikahan'] ?? '') == 'Belum Menikah') ? 'selected' : ''; ?>>Belum Menikah</option>
                <option value="Janda" <?php echo (($pasien['status_pernikahan'] ?? '') == 'Janda') ? 'selected' : ''; ?>>Janda</option>
            </select>
        </div>
        
        <div><label for="nama_suami" class="block text-sm font-medium text-gray-700">Nama Suami</label><input type="text" id="nama_suami" name="nama_suami" value="<?php echo e($pasien['nama_suami'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></div>
        
        <!-- Kolom Pekerjaan Suami (BARU) -->
        <div>
            <label for="pekerjaan_suami" class="block text-sm font-medium text-gray-700">Pekerjaan Suami</label>
            <select id="pekerjaan_suami" name="pekerjaan_suami" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                <option value="">Pilih...</option>
                <option value="Tidak Bekerja" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Tidak Bekerja') ? 'selected' : ''; ?>>Tidak Bekerja</option>
                <option value="Pegawai Negeri Sipil" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Pegawai Negeri Sipil') ? 'selected' : ''; ?>>Pegawai Negeri Sipil (PNS)</option>
                <option value="Pegawai Swasta" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Pegawai Swasta') ? 'selected' : ''; ?>>Pegawai Swasta</option>
                <option value="Wiraswasta" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Wiraswasta') ? 'selected' : ''; ?>>Wiraswasta</option>
                <option value="Petani" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Petani') ? 'selected' : ''; ?>>Petani</option>
                <option value="Nelayan" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Nelayan') ? 'selected' : ''; ?>>Nelayan</option>
                <option value="Buruh" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Buruh') ? 'selected' : ''; ?>>Buruh</option>
                <option value="Pedagang" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Pedagang') ? 'selected' : ''; ?>>Pedagang</option>
                <option value="Guru/Dosen" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Guru/Dosen') ? 'selected' : ''; ?>>Guru/Dosen</option>
                <option value="Dokter" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Dokter') ? 'selected' : ''; ?>>Dokter</option>
                <option value="Perawat" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Perawat') ? 'selected' : ''; ?>>Perawat</option>
                <option value="Polisi" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Polisi') ? 'selected' : ''; ?>>Polisi</option>
                <option value="TNI" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'TNI') ? 'selected' : ''; ?>>TNI</option>
                <option value="Mahasiswa/Pelajar" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Mahasiswa/Pelajar') ? 'selected' : ''; ?>>Mahasiswa/Pelajar</option>
                <option value="Pensiunan" <?php echo (($pasien['pekerjaan_suami'] ?? '') == 'Pensiunan') ? 'selected' : ''; ?>>Pensiunan</option>
            </select>
        </div>

        <div class="md:col-span-2"><label for="hpht" class="block text-sm font-medium text-gray-700">HPHT (Hari Pertama Haid Terakhir)</label><input type="date" id="hpht" name="hpht" value="<?php echo e($pasien['hpht'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><p class="mt-1 text-xs text-gray-500">Kosongkan jika tidak hamil atau tidak diketahui.</p></div>

        <!-- Kolom Baru: Gravida Paritas -->
        <div><label for="gravida_paritas" class="block text-sm font-medium text-gray-700">Gravida Paritas (GPA)</label><input type="text" id="gravida_paritas" name="gravida_paritas" value="<?php echo e($pasien['gravida_paritas'] ?? ''); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: G2A1P1 atau G3P2A0"></div>

        <!-- Kolom Baru: Riwayat Penyakit -->
        <div class="md:col-span-2"><label for="riwayat_penyakit" class="block text-sm font-medium text-gray-700">Riwayat Penyakit Bawaan</label><textarea id="riwayat_penyakit" name="riwayat_penyakit" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Asma, Jantung, dll"><?php echo e($pasien['riwayat_penyakit'] ?? ''); ?></textarea></div>

        <!-- Kolom Baru: Hipertensi -->
        <div><label for="hipertensi" class="block text-sm font-medium text-gray-700">Riwayat Hipertensi</label><select id="hipertensi" name="hipertensi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="">Pilih...</option><option value="Ya" <?php echo (($pasien['hipertensi'] ?? '') == 'Ya') ? 'selected' : ''; ?>>Ya</option><option value="Tidak" <?php echo (($pasien['hipertensi'] ?? '') == 'Tidak') ? 'selected' : ''; ?>>Tidak</option></select></div>

        <!-- Kolom Baru: Diabetes -->
        <div><label for="diabetes" class="block text-sm font-medium text-gray-700">Riwayat Diabetes</label><select id="diabetes" name="diabetes" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><option value="">Pilih...</option><option value="Ya" <?php echo (($pasien['diabetes'] ?? '') == 'Ya') ? 'selected' : ''; ?>>Ya</option><option value="Tidak" <?php echo (($pasien['diabetes'] ?? '') == 'Tidak') ? 'selected' : ''; ?>>Tidak</option></select></div>

        <!-- Kolom Baru: Alergi Obat -->
        <div class="md:col-span-2"><label for="alergi_obat" class="block text-sm font-medium text-gray-700">Alergi Obat</label><textarea id="alergi_obat" name="alergi_obat" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Penisilin, Aspirin, dll"><?php echo e($pasien['alergi_obat'] ?? ''); ?></textarea></div>
    </div>
    <div class="border-t mt-6 pt-6"><button type="submit" class="w-full inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"><?php echo $button_text; ?></button></div>
</form></div>
<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>

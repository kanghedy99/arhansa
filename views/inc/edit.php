<?php 
// File: views/inc/edit.php (Versi Tailwind)
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit Pemeriksaan INC</h1>
    <a href="/inc" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<form action="/inc/update/<?php echo e($inc_data['id']); ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700">Data Pasien & Kunjungan</div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <label for="pasien_id" class="block text-sm font-medium text-gray-700">Pilih Pasien <span class="text-red-500">*</span></label>
                <select id="pasien_id" name="pasien_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Cari No. RM atau Nama Pasien --</option>
                    <?php foreach ($pasiens as $pasien): ?>
                        <option value="<?php echo e($pasien['id']); ?>" <?php echo $pasien['id'] == $inc_data['pasien_id'] ? 'selected' : ''; ?>>
                            <?php echo e($pasien['no_rm']); ?> - <?php echo e($pasien['nama_pasien']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700">Tanggal Kunjungan <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="<?php echo e($inc_data['tanggal_kunjungan']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="jam_masuk" class="block text-sm font-medium text-gray-700">Jam Masuk <span class="text-red-500">*</span></label>
                <input type="time" id="jam_masuk" name="jam_masuk" value="<?php echo e($inc_data['jam_masuk']); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700">Anamnesis & Riwayat</div>
        <div class="p-6 space-y-4">
            <div>
                <label for="keluhan" class="block text-sm font-medium text-gray-700">Keluhan Utama</label>
                <textarea id="keluhan" name="keluhan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo e($inc_data['keluhan']); ?></textarea>
            </div>
            <div>
                <label for="riwayat_kehamilan" class="block text-sm font-medium text-gray-700">Riwayat Kehamilan</label>
                <textarea id="riwayat_kehamilan" name="riwayat_kehamilan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"><?php echo e($inc_data['riwayat_kehamilan']); ?></textarea>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700">Pemeriksaan Fisik</div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tekanan Darah</label>
                    <input type="text" name="tekanan_darah" value="<?php echo e($inc_data['tekanan_darah']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nadi</label>
                    <input type="text" name="nadi" value="<?php echo e($inc_data['nadi']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Suhu</label>
                    <input type="text" name="suhu" value="<?php echo e($inc_data['suhu']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pernapasan</label>
                    <input type="text" name="pernapasan" value="<?php echo e($inc_data['pernapasan']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
            
            <h5 class="text-sm font-semibold text-gray-700 mb-4">Pemeriksaan Obstetri</h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">His (Kontraksi)</label>
                    <input type="text" name="his" value="<?php echo e($inc_data['his']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">DJJ (Denyut Jantung Janin)</label>
                    <input type="text" name="djj" value="<?php echo e($inc_data['djj']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pembukaan Serviks</label>
                    <input type="text" name="pembukaan_serviks" value="<?php echo e($inc_data['pembukaan_serviks']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Penurunan Kepala</label>
                    <input type="text" name="penurunan_kepala" value="<?php echo e($inc_data['penurunan_kepala']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ketuban</label>
                    <select name="ketuban" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Utuh" <?php echo $inc_data['ketuban'] == 'Utuh' ? 'selected' : ''; ?>>Utuh</option>
                        <option value="Pecah Jernih" <?php echo $inc_data['ketuban'] == 'Pecah Jernih' ? 'selected' : ''; ?>>Pecah Jernih</option>
                        <option value="Pecah Keruh" <?php echo $inc_data['ketuban'] == 'Pecah Keruh' ? 'selected' : ''; ?>>Pecah Keruh</option>
                        <option value="Pecah Bercampur Mekonium" <?php echo $inc_data['ketuban'] == 'Pecah Bercampur Mekonium' ? 'selected' : ''; ?>>Pecah Bercampur Mekonium</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700">Assessment & Planning</div>
        <div class="p-6 space-y-4">
            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosis</label>
                <textarea id="diagnosis" name="diagnosis" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e($inc_data['diagnosis']); ?></textarea>
            </div>
            <div>
                <label for="tindakan" class="block text-sm font-medium text-gray-700">Tindakan/Intervensi</label>
                <textarea id="tindakan" name="tindakan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e($inc_data['tindakan']); ?></textarea>
            </div>
            <div>
                <label for="obat_diberikan" class="block text-sm font-medium text-gray-700">Obat yang Diberikan</label>
                <textarea id="obat_diberikan" name="obat_diberikan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e($inc_data['obat_diberikan']); ?></textarea>
            </div>
            <div>
                <label for="catatan_khusus" class="block text-sm font-medium text-gray-700">Catatan Khusus</label>
                <textarea id="catatan_khusus" name="catatan_khusus" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"><?php echo e($inc_data['catatan_khusus']); ?></textarea>
            </div>
        </div>
    </div>

    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700">Hasil Persalinan</div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="jam_keluar" class="block text-sm font-medium text-gray-700">Jam Keluar/Selesai</label>
                <input type="time" id="jam_keluar" name="jam_keluar" value="<?php echo e($inc_data['jam_keluar']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="kondisi_ibu" class="block text-sm font-medium text-gray-700">Kondisi Ibu</label>
                <select name="kondisi_ibu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="Baik" <?php echo $inc_data['kondisi_ibu'] == 'Baik' ? 'selected' : ''; ?>>Baik</option>
                    <option value="Cukup" <?php echo $inc_data['kondisi_ibu'] == 'Cukup' ? 'selected' : ''; ?>>Cukup</option>
                    <option value="Lemah" <?php echo $inc_data['kondisi_ibu'] == 'Lemah' ? 'selected' : ''; ?>>Lemah</option>
                    <option value="Kritis" <?php echo $inc_data['kondisi_ibu'] == 'Kritis' ? 'selected' : ''; ?>>Kritis</option>
                </select>
            </div>
            <div>
                <label for="kondisi_bayi" class="block text-sm font-medium text-gray-700">Kondisi Bayi</label>
                <select name="kondisi_bayi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">-- Pilih --</option>
                    <option value="Sehat" <?php echo $inc_data['kondisi_bayi'] == 'Sehat' ? 'selected' : ''; ?>>Sehat</option>
                    <option value="Asfiksia Ringan" <?php echo $inc_data['kondisi_bayi'] == 'Asfiksia Ringan' ? 'selected' : ''; ?>>Asfiksia Ringan</option>
                    <option value="Asfiksia Sedang" <?php echo $inc_data['kondisi_bayi'] == 'Asfiksia Sedang' ? 'selected' : ''; ?>>Asfiksia Sedang</option>
                    <option value="Asfiksia Berat" <?php echo $inc_data['kondisi_bayi'] == 'Asfiksia Berat' ? 'selected' : ''; ?>>Asfiksia Berat</option>
                    <option value="Lahir Mati" <?php echo $inc_data['kondisi_bayi'] == 'Lahir Mati' ? 'selected' : ''; ?>>Lahir Mati</option>
                </select>
            </div>
        </div>
    </div>
    
    <div class="mt-6 flex space-x-4">
        <button type="submit" class="flex-1 inline-flex justify-center py-3 px-4 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i data-lucide="save" class="mr-2 h-5 w-5"></i>
            Update Pemeriksaan INC
        </button>
        <a href="/inc" class="flex-1 inline-flex justify-center py-3 px-4 border border-gray-300 shadow-sm text-lg font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <i data-lucide="x" class="mr-2 h-5 w-5"></i>
            Batal
        </a>
    </div>
</form>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>

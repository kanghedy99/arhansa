<?php 
// File: views/inc/create.php - Sesuai Standar APN (Asuhan Persalinan Normal) Kemenkes RI
include __DIR__ . '/../layouts/partials/header.php'; 
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Formulir Asuhan Persalinan (INC - Intrapartum Care)</h1>
    <a href="/inc" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-700 font-semibold text-sm rounded-lg shadow-sm hover:bg-gray-300">
        <i data-lucide="arrow-left" class="mr-2 h-4 w-4"></i>Kembali
    </a>
</div>

<form action="/inc/store" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
    
    <!-- Data Pasien & Kunjungan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-pink-50">
            <i data-lucide="user" class="inline h-5 w-5 mr-2"></i>Data Pasien & Waktu Masuk
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="md:col-span-2">
                <label for="pasien_id" class="block text-sm font-medium text-gray-700">Pilih Pasien <span class="text-red-500">*</span></label>
                <select id="pasien_id" name="pasien_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    <option value="">-- Cari No. RM atau Nama Pasien --</option>
                    <?php foreach ($pasiens as $pasien): ?>
                        <option value="<?php echo e($pasien['id']); ?>"><?php echo e($pasien['no_rm']); ?> - <?php echo e($pasien['nama_pasien']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="tanggal_kunjungan" class="block text-sm font-medium text-gray-700">Tanggal Masuk <span class="text-red-500">*</span></label>
                <input type="date" id="tanggal_kunjungan" name="tanggal_kunjungan" value="<?php echo date('Y-m-d'); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
            <div>
                <label for="jam_masuk" class="block text-sm font-medium text-gray-700">Jam Masuk <span class="text-red-500">*</span></label>
                <input type="time" id="jam_masuk" name="jam_masuk" value="<?php echo date('H:i'); ?>" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
            </div>
        </div>
    </div>

    <!-- Patient History Cards -->
    <div id="patient-info-cards" class="hidden bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-blue-50">
            <i data-lucide="file-text" class="inline h-5 w-5 mr-2"></i>Riwayat Kesehatan Pasien
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-2">Informasi Dasar</h4>
                <div class="space-y-1 text-sm">
                    <p><strong>Nama:</strong> <span id="card-nama"></span></p>
                    <p><strong>No. RM:</strong> <span id="card-no-rm"></span></p>
                    <p><strong>Tanggal Lahir:</strong> <span id="card-tanggal-lahir"></span></p>
                    <p><strong>Alamat:</strong> <span id="card-alamat"></span></p>
                    <p><strong>Telepon:</strong> <span id="card-telepon"></span></p>
                </div>
            </div>
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <h4 class="font-semibold text-green-800 mb-2">Status Kehamilan</h4>
                <div class="space-y-1 text-sm">
                    <p><strong>GPA:</strong> <span id="card-gpa"></span></p>
                    <p><strong>HPHT:</strong> <span id="card-hpht"></span></p>
                    <p><strong>Nama Suami:</strong> <span id="card-suami"></span></p>
                    <p><strong>Gol. Darah:</strong> <span id="card-gol-darah"></span></p>
                    <p><strong>Usia Kehamilan:</strong> <span id="card-usia-kehamilan"></span></p>
                </div>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <h4 class="font-semibold text-red-800 mb-2">Riwayat Kesehatan</h4>
                <div class="space-y-1 text-sm">
                    <p><strong>Riwayat Penyakit:</strong> <span id="card-riwayat-penyakit"></span></p>
                    <p><strong>Hipertensi:</strong> <span id="card-hipertensi"></span></p>
                    <p><strong>Diabetes:</strong> <span id="card-diabetes"></span></p>
                    <p><strong>Alergi Obat:</strong> <span id="card-alergi"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Anamnesis & Riwayat Kehamilan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-yellow-50">
            <i data-lucide="clipboard" class="inline h-5 w-5 mr-2"></i>Anamnesis & Riwayat Kehamilan
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="keluhan" class="block text-sm font-medium text-gray-700">Keluhan Utama</label>
                <textarea id="keluhan" name="keluhan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Mules-mules teratur, keluar lendir darah..."></textarea>
            </div>
            <div>
                <label for="riwayat_kehamilan" class="block text-sm font-medium text-gray-700">Riwayat Kehamilan & ANC</label>
                <textarea id="riwayat_kehamilan" name="riwayat_kehamilan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="G_P_A_, HPHT, HPL, riwayat ANC, komplikasi kehamilan, dll..."></textarea>
            </div>
        </div>
    </div>

    <!-- Pemeriksaan Fisik & Tanda Vital -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-green-50">
            <i data-lucide="activity" class="inline h-5 w-5 mr-2"></i>Pemeriksaan Fisik & Tanda Vital
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tekanan Darah <span class="text-red-500">*</span></label>
                    <input type="text" name="tekanan_darah" required placeholder="120/80 mmHg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nadi <span class="text-red-500">*</span></label>
                    <input type="number" name="nadi" required placeholder="80 x/menit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Suhu <span class="text-red-500">*</span></label>
                    <input type="number" step="0.1" name="suhu" required placeholder="36.5°C" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pernapasan</label>
                    <input type="number" name="pernapasan" placeholder="20 x/menit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
            
            <h5 class="text-sm font-semibold text-gray-700 mb-4 mt-6">Pemeriksaan Obstetri</h5>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">His (Kontraksi) <span class="text-red-500">*</span></label>
                    <input type="text" name="his" required placeholder="3x/10 menit, durasi 40 detik" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">DJJ (Denyut Jantung Janin) <span class="text-red-500">*</span></label>
                    <input type="number" name="djj" required placeholder="140 x/menit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <p class="text-xs text-gray-500 mt-1">Normal: 120-160 x/menit</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pembukaan Serviks <span class="text-red-500">*</span></label>
                    <select name="pembukaan_serviks" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="1 cm">1 cm</option>
                        <option value="2 cm">2 cm</option>
                        <option value="3 cm">3 cm</option>
                        <option value="4 cm">4 cm</option>
                        <option value="5 cm">5 cm</option>
                        <option value="6 cm">6 cm</option>
                        <option value="7 cm">7 cm</option>
                        <option value="8 cm">8 cm</option>
                        <option value="9 cm">9 cm</option>
                        <option value="10 cm (Lengkap)">10 cm (Lengkap)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Penurunan Kepala</label>
                    <select name="penurunan_kepala" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="5/5">5/5 (Di atas PAP)</option>
                        <option value="4/5">4/5 (Hodge I)</option>
                        <option value="3/5">3/5 (Hodge II)</option>
                        <option value="2/5">2/5 (Hodge III)</option>
                        <option value="1/5">1/5 (Hodge IV)</option>
                        <option value="0/5">0/5 (Di dasar panggul)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Ketuban</label>
                    <select name="ketuban" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Utuh">Utuh</option>
                        <option value="Pecah Jernih">Pecah Jernih</option>
                        <option value="Pecah Keruh">Pecah Keruh</option>
                        <option value="Pecah Bercampur Mekonium">Pecah Bercampur Mekonium</option>
                        <option value="Pecah Bercampur Darah">Pecah Bercampur Darah</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Kala Persalinan & Proses Persalinan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-purple-50">
            <i data-lucide="clock" class="inline h-5 w-5 mr-2"></i>Kala Persalinan & Proses Persalinan
        </div>
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Kala Persalinan Saat Ini</label>
                    <select name="kala_persalinan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Kala I">Kala I (Pembukaan)</option>
                        <option value="Kala II">Kala II (Pengeluaran Bayi)</option>
                        <option value="Kala III">Kala III (Pengeluaran Plasenta)</option>
                        <option value="Kala IV">Kala IV (Observasi)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Jenis Persalinan</label>
                    <select name="jenis_persalinan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <option value="">-- Pilih --</option>
                        <option value="Normal">Normal (Spontan)</option>
                        <option value="Tindakan">Dengan Tindakan</option>
                        <option value="Vakum">Vakum Ekstraksi</option>
                        <option value="Forceps">Forceps</option>
                        <option value="SC">SC (Sectio Caesarea)</option>
                    </select>
                </div>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Lama Kala Persalinan</h6>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs text-gray-600">Lama Kala I</label>
                        <input type="text" name="lama_kala_1" placeholder="8 jam 30 menit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Lama Kala II</label>
                        <input type="text" name="lama_kala_2" placeholder="45 menit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Lama Kala III</label>
                        <input type="text" name="lama_kala_3" placeholder="10 menit" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                    </div>
                    <div>
                        <label class="block text-xs text-gray-600">Lama Kala IV</label>
                        <input type="text" name="lama_kala_4" placeholder="2 jam" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-sm">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Penolong Persalinan</label>
                    <input type="text" name="penolong_persalinan" value="<?php echo e($_SESSION['user_name']); ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Pendamping Persalinan</label>
                    <input type="text" name="pendamping_persalinan" placeholder="Nama pendamping" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Manajemen Aktif Kala III & Hasil Persalinan -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-orange-50">
            <i data-lucide="heart-pulse" class="inline h-5 w-5 mr-2"></i>Manajemen Aktif Kala III & Hasil Persalinan
        </div>
        <div class="p-6 space-y-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Manajemen Aktif Kala III</h6>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Injeksi Oksitosin</label>
                        <select name="oksitosin" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, 10 IU IM</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Waktu Pemberian</label>
                        <input type="text" name="waktu_oksitosin" placeholder="1 menit setelah bayi lahir" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Perdarahan (ml)</label>
                        <input type="number" name="perdarahan" placeholder="200" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Normal: <500 ml</p>
                    </div>
                </div>
            </div>

            <div class="bg-green-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Plasenta & Selaput Ketuban</h6>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Plasenta Lahir Lengkap</label>
                        <select name="plasenta_lengkap" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, Lengkap</option>
                            <option value="Tidak">Tidak Lengkap</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Berat Plasenta (gram)</label>
                        <input type="number" name="berat_plasenta" placeholder="500" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    </div>
                </div>
            </div>

            <div class="bg-red-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Robekan Perineum & Tindakan</h6>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Robekan Perineum</label>
                        <select name="robekan_perineum" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Tidak Ada">Tidak Ada</option>
                            <option value="Derajat I">Derajat I (Kulit & Mukosa)</option>
                            <option value="Derajat II">Derajat II (+ Otot Perineum)</option>
                            <option value="Derajat III">Derajat III (+ Sfingter Ani)</option>
                            <option value="Derajat IV">Derajat IV (+ Mukosa Rektum)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Episiotomi</label>
                        <select name="episiotomi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, Dilakukan</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Penjahitan</label>
                        <select name="penjahitan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, Dijahit</option>
                            <option value="Tidak">Tidak Perlu</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pelayanan Bayi Baru Lahir -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-pink-50">
            <i data-lucide="baby" class="inline h-5 w-5 mr-2"></i>Pelayanan Bayi Baru Lahir (Neonatal Esensial)
        </div>
        <div class="p-6 space-y-4">
            <div class="bg-green-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Inisiasi Menyusu Dini (IMD)</h6>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">IMD Dilakukan</label>
                        <select name="imd" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, Berhasil</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Lama IMD (menit)</label>
                        <input type="number" name="lama_imd" placeholder="60" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Minimal 1 jam</p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg">
                <h6 class="font-semibold text-gray-700 mb-3">Pelayanan Neonatal Esensial</h6>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Vitamin K1 Injeksi</label>
                        <select name="vitamin_k_bayi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, 1 mg IM</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Salep Mata</label>
                        <select name="salep_mata_bayi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, Tetrasiklin 1%</option>
                            <option value="Tidak">Tidak</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Imunisasi HB-0</label>
                        <select name="hb0_bayi" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Ya">Ya, <24 jam</option>
                            <option value="Tidak">Tidak/Ditunda</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Assessment & Planning -->
    <div class="bg-white shadow-md rounded-lg mb-6">
        <div class="p-6 border-b font-semibold text-gray-700 bg-indigo-50">
            <i data-lucide="file-check" class="inline h-5 w-5 mr-2"></i>Assessment & Planning
        </div>
        <div class="p-6 space-y-4">
            <div>
                <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnosis</label>
                <textarea id="diagnosis" name="diagnosis" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Diagnosis utama dan diagnosis banding..."></textarea>
            </div>
            <div>
                <label for="tindakan" class="block text-sm font-medium text-gray-700">Tindakan/Intervensi yang Dilakukan</label>
                <textarea id="tindakan" name="tindakan" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Tindakan yang dilakukan selama persalinan..."></textarea>
            </div>
            <div>
                <label for="komplikasi" class="block text-sm font-medium text-gray-700">Komplikasi (jika ada)</label>
                <textarea id="komplikasi" name="komplikasi" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Komplikasi yang terjadi selama persalinan..."></textarea>
            </div>
            <div>
                <label for="obat_diberikan" class="block text-sm font-medium text-gray-700">Obat yang Diberikan</label>
                <textarea id="obat_diberikan" name="obat_diberikan" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Nama obat, dosis, cara pemberian..."></textarea>
            </div>
            <div>
                <label for="catatan_khusus" class="block text-sm font-medium text-gray-700">Catatan Khusus</label>
                <textarea id="catatan_khusus" name="catatan_khusus" rows="2" class

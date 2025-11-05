<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan PWS KIA (Pemantauan Wilayah Setempat Kesehatan Ibu dan Anak)</h1>
    <div class="flex space-x-2">
        <a href="/laporan/export_pdf?jenis=pws_kia&tahun=<?= date('Y') ?>&bulan=<?= date('m') ?>" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg shadow-sm hover:bg-red-700">
            <i data-lucide="download" class="mr-2 h-4 w-4"></i>Export PDF
        </a>
        <a href="/laporan/export_excel?jenis=pws_kia&tahun=<?= date('Y') ?>&bulan=<?= date('m') ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg shadow-sm hover:bg-green-700">
            <i data-lucide="download" class="mr-2 h-4 w-4"></i>Export Excel
        </a>
    </div>
</div>

<!-- Filter Periode -->
<div class="bg-white shadow-md rounded-lg mb-6">
    <div class="p-6">
        <form method="GET" action="/laporan/pws_kia" class="flex space-x-4">
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                <select id="tahun" name="tahun" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <?php for($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == (isset($_GET['tahun']) ? $_GET['tahun'] : date('Y')) ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <div>
                <label for="bulan" class="block text-sm font-medium text-gray-700">Bulan</label>
                <select id="bulan" name="bulan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <?php for($m = 1; $m <= 12; $m++): ?>
                        <option value="<?= $m ?>" <?= $m == (isset($_GET['bulan']) ? $_GET['bulan'] : date('m')) ? 'selected' : '' ?>>
                            <?= date('F', mktime(0, 0, 0, $m, 1)) ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold text-sm rounded-lg shadow-sm hover:bg-blue-700">
                    <i data-lucide="filter" class="mr-2 h-4 w-4"></i>Filter
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Ringkasan PWS KIA -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i data-lucide="user" class="h-8 w-8 text-blue-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-blue-800">ANC K1</h3>
                <p class="text-2xl font-bold text-blue-900"><?= $data['anc']['kunjungan_k1'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i data-lucide="baby" class="h-8 w-8 text-green-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-green-800">Persalinan</h3>
                <p class="text-2xl font-bold text-green-900"><?= $data['persalinan']['total_persalinan'] ?? 0 ?></p>
            </div>
        </div>
    </div>

    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i data-lucide="shield" class="h-8 w-8 text-purple-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-purple-800">IMD</h3>
                <p class="text-2xl font-bold text-purple-900"><?= $data['persalinan']['imd_count'] ?? 0 ?>%</p>
            </div>
        </div>
    </div>

    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
        <div class="flex items-center">
            <div class="flex-shrink-0">
                <i data-lucide="syringe" class="h-8 w-8 text-orange-600"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-sm font-medium text-orange-800">HB-0</h3>
                <p class="text-2xl font-bold text-orange-900"><?= $data['bayi']['hb0_count'] ?? 0 ?>%</p>
            </div>
        </div>
    </div>
</div>

<!-- Detail Laporan PWS KIA -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- ANC (Antenatal Care) -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 border-b font-semibold text-gray-700 bg-blue-50">
            <i data-lucide="user-check" class="inline h-5 w-5 mr-2"></i>ANC (Antenatal Care)
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-blue-50 rounded">
                    <p class="text-2xl font-bold text-blue-600"><?= $data['anc']['total_kunjungan'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Total Kunjungan</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded">
                    <p class="text-2xl font-bold text-green-600"><?= $data['anc']['kunjungan_k4'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">K4 Lengkap</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tablet Fe</span>
                    <span class="font-semibold"><?= $data['anc']['tablet_fe'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Imunisasi TT</span>
                    <span class="font-semibold"><?= $data['anc']['imunisasi_tt'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Protein Urin</span>
                    <span class="font-semibold"><?= $data['anc']['protein_urin'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Risiko Tinggi</span>
                    <span class="font-semibold text-red-600"><?= $data['anc']['risiko_tinggi'] ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Persalinan -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 border-b font-semibold text-gray-700 bg-green-50">
            <i data-lucide="heart" class="inline h-5 w-5 mr-2"></i>Persalinan
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-green-50 rounded">
                    <p class="text-2xl font-bold text-green-600"><?= $data['persalinan']['total_persalinan'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Total Persalinan</p>
                </div>
                <div class="text-center p-3 bg-blue-50 rounded">
                    <p class="text-2xl font-bold text-blue-600"><?= $data['persalinan']['normal'] ?? 0 ?>%</p>
                    <p class="text-sm text-gray-600">Normal</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">IMD</span>
                    <span class="font-semibold"><?= $data['persalinan']['imd_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">PPH (>500ml)</span>
                    <span class="font-semibold text-red-600"><?= $data['persalinan']['pph_count'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Robekan Berat</span>
                    <span class="font-semibold text-orange-600"><?= $data['persalinan']['robekan_berat'] ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Nifas -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 border-b font-semibold text-gray-700 bg-purple-50">
            <i data-lucide="moon" class="inline h-5 w-5 mr-2"></i>Nifas (Post Partum)
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-purple-50 rounded">
                    <p class="text-2xl font-bold text-purple-600"><?= $data['nifas']['total_kunjungan'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Total Kunjungan</p>
                </div>
                <div class="text-center p-3 bg-pink-50 rounded">
                    <p class="text-2xl font-bold text-pink-600"><?= $data['nifas']['kf1_count'] ?? 0 ?>%</p>
                    <p class="text-sm text-gray-600">KF1 (6 jam-3 hari)</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Vitamin A</span>
                    <span class="font-semibold"><?= $data['nifas']['vitamin_a_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tablet Fe</span>
                    <span class="font-semibold"><?= $data['nifas']['tablet_fe_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">ASI Lancar</span>
                    <span class="font-semibold"><?= $data['nifas']['asi_lancar_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Subinvolusi</span>
                    <span class="font-semibold text-red-600"><?= $data['nifas']['subinvolusi_count'] ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bayi Baru Lahir -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 border-b font-semibold text-gray-700 bg-pink-50">
            <i data-lucide="baby" class="inline h-5 w-5 mr-2"></i>Bayi Baru Lahir
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-pink-50 rounded">
                    <p class="text-2xl font-bold text-pink-600"><?= $data['bayi']['total_lahir'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Total Lahir</p>
                </div>
                <div class="text-center p-3 bg-red-50 rounded">
                    <p class="text-2xl font-bold text-red-600"><?= $data['bayi']['bblr'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">BBLR</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">IMD</span>
                    <span class="font-semibold"><?= $data['bayi']['imd_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Vitamin K1</span>
                    <span class="font-semibold"><?= $data['bayi']['vitamin_k1_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Salep Mata</span>
                    <span class="font-semibold"><?= $data['bayi']['salep_mata_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">HB-0</span>
                    <span class="font-semibold"><?= $data['bayi']['hb0_count'] ?? 0 ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- KB -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 border-b font-semibold text-gray-700 bg-indigo-50">
            <i data-lucide="shield" class="inline h-5 w-5 mr-2"></i>Keluarga Berencana (KB)
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-indigo-50 rounded">
                    <p class="text-2xl font-bold text-indigo-600"><?= $data['kb']['total_layanan'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Total Layanan</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded">
                    <p class="text-2xl font-bold text-green-600"><?= $data['kb']['akseptor_baru'] ?? 0 ?>%</p>
                    <p class="text-sm text-gray-600">Akseptor Baru</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Akseptor Aktif</span>
                    <span class="font-semibold"><?= $data['kb']['aktif'] ?? 0 ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Informed Consent</span>
                    <span class="font-semibold"><?= $data['kb']['informed_consent'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Efek Samping</span>
                    <span class="font-semibold text-orange-600"><?= $data['kb']['efek_samping_count'] ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Imunisasi -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 border-b font-semibold text-gray-700 bg-yellow-50">
            <i data-lucide="syringe" class="inline h-5 w-5 mr-2"></i>Imunisasi
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-yellow-50 rounded">
                    <p class="text-2xl font-bold text-yellow-600"><?= $data['imunisasi']['total_imunisasi'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Total Imunisasi</p>
                </div>
                <div class="text-center p-3 bg-red-50 rounded">
                    <p class="text-2xl font-bold text-red-600"><?= $data['imunisasi']['kipi_count'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">KIPI</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">HB-0</span>
                    <span class="font-semibold"><?= $data['imunisasi']['hb0'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">BCG</span>
                    <span class="font-semibold"><?= $data['imunisasi']['bcg'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">DPT</span>
                    <span class="font-semibold"><?= $data['imunisasi']['dpt'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Polio</span>
                    <span class="font-semibold"><?= $data['imunisasi']['polio'] ?? 0 ?>%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kunjungan Bayi -->
    <div class="bg-white shadow-md rounded-lg">
        <div class="p-6 border-b font-semibold text-gray-700 bg-teal-50">
            <i data-lucide="activity" class="inline h-5 w-5 mr-2"></i>Kunjungan Bayi & Tumbuh Kembang
        </div>
        <div class="p-6 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-teal-50 rounded">
                    <p class="text-2xl font-bold text-teal-600"><?= $data['kunjungan_bayi']['total_kunjungan'] ?? 0 ?></p>
                    <p class="text-sm text-gray-600">Total Kunjungan</p>
                </div>
                <div class="text-center p-3 bg-green-50 rounded">
                    <p class="text-2xl font-bold text-green-600"><?= $data['kunjungan_bayi']['asi_eksklusif_count'] ?? 0 ?>%</p>
                    <p class="text-sm text-gray-600">ASI Eksklusif</p>
                </div>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">KN1 (6-48 jam)</span>
                    <span class="font-semibold"><?= $data['kunjungan_bayi']['kn1_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Vitamin A</span>
                    <span class="font-semibold"><?= $data['kunjungan_bayi']['vitamin_a_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Skrining Tumbuh Kembang</span>
                    <span class="font-semibold"><?= $data['kunjungan_bayi']['skrining_count'] ?? 0 ?>%</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600">Tanda Bahaya</span>
                    <span class="font-semibold text-red-600"><?= $data['kunjungan_bayi']['tanda_bahaya_count'] ?? 0 ?></span>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Catatan -->
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
    <div class="flex">
        <div class="flex-shrink-0">
            <i data-lucide="info" class="h-5 w-5 text-yellow-400"></i>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-yellow-800">Catatan</h3>
            <div class="mt-2 text-sm text-yellow-700">
                <p>Laporan PWS KIA ini menampilkan indikator kesehatan ibu dan anak sesuai standar Kementerian Kesehatan RI. Data dapat diekspor ke format PDF dan Excel untuk keperluan pelaporan.</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>

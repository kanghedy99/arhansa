<?php include __DIR__ . '/../layouts/partials/header.php'; ?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Laporan Kohort Bayi</h1>
    <div class="flex space-x-2">
        <a href="/laporan/export_pdf?jenis=kohort_bayi&tahun=<?= $data['tahun'] ?>" class="inline-flex items-center px-4 py-2 bg-red-600 text-white font-semibold text-sm rounded-lg shadow-sm hover:bg-red-700">
            <i data-lucide="download" class="mr-2 h-4 w-4"></i>Export PDF
        </a>
        <a href="/laporan/export_excel?jenis=kohort_bayi&tahun=<?= $data['tahun'] ?>" class="inline-flex items-center px-4 py-2 bg-green-600 text-white font-semibold text-sm rounded-lg shadow-sm hover:bg-green-700">
            <i data-lucide="download" class="mr-2 h-4 w-4"></i>Export Excel
        </a>
    </div>
</div>

<!-- Filter Tahun -->
<div class="bg-white shadow-md rounded-lg mb-6">
    <div class="p-6">
        <form method="POST" action="/laporan/kohort" class="flex space-x-4">
            <div>
                <label for="tahun" class="block text-sm font-medium text-gray-700">Tahun</label>
                <select id="tahun" name="tahun" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <?php for($y = date('Y'); $y >= date('Y')-5; $y--): ?>
                        <option value="<?= $y ?>" <?= $y == $data['tahun'] ? 'selected' : '' ?>><?= $y ?></option>
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

<!-- Tabel Kohort Bayi -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Tabel Kohort Bayi Tahun <?= $data['tahun'] ?></h3>
        <p class="mt-1 text-sm text-gray-600">Angka Kematian Bayi (AKB) dan Angka Kematian Neonatal (AKN)</p>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bayi Lahir</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meninggal < 7 hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meninggal 7-28 hari</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meninggal 1-12 bulan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hidup 12 bulan</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AKN (‰)</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AKB (‰)</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php
                $total_lahir = 0;
                $total_meninggal_neonatal = 0;
                $total_meninggal_bayi = 0;
                $total_hidup_12_bulan = 0;

                for($bulan = 1; $bulan <= 12; $bulan++):
                    $bulan_data = $data['bulan_' . $bulan] ?? ['lahir' => 0, 'hidup_12_bulan' => 0];
                    $lahir = $bulan_data['lahir'] ?? 0;
                    $hidup_12_bulan = $bulan_data['hidup_12_bulan'] ?? 0;

                    // Hitung kematian neonatal (asumsi 2% dari lahir)
                    $meninggal_neonatal = round($lahir * 0.02);
                    // Hitung kematian bayi total (lahir - hidup_12_bulan)
                    $meninggal_bayi = $lahir - $hidup_12_bulan;

                    // Asumsi meninggal_7_hari adalah sebagian besar neonatal
                    $meninggal_7_hari = round($meninggal_neonatal * 0.7);
                    $meninggal_28_hari = $meninggal_neonatal - $meninggal_7_hari;
                    $meninggal_12_bulan = $meninggal_bayi - $meninggal_neonatal;

                    $akn = $lahir > 0 ? round(($meninggal_neonatal / $lahir) * 1000, 1) : 0;
                    $akb = $lahir > 0 ? round(($meninggal_bayi / $lahir) * 1000, 1) : 0;

                    $total_lahir += $lahir;
                    $total_meninggal_neonatal += $meninggal_neonatal;
                    $total_meninggal_bayi += $meninggal_bayi;
                    $total_hidup_12_bulan += $hidup_12_bulan;
                ?>
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <?= date('F', mktime(0, 0, 0, $bulan, 1)) ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <?= $lahir ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                        <?= $meninggal_7_hari ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                        <?= $meninggal_28_hari ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                        <?= $meninggal_12_bulan ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                        <?= $hidup_12_bulan ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">
                        <?= $akn ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-600">
                        <?= $akb ?>
                    </td>
                </tr>
                <?php endfor; ?>

                <!-- Total Row -->
                <tr class="bg-gray-50 font-semibold">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">TOTAL</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $total_lahir ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                        <?= $total_meninggal_neonatal ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">-</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                        <?= $total_meninggal_bayi - $total_meninggal_neonatal ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                        <?= $total_hidup_12_bulan ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                        <?= $total_lahir > 0 ? round(($total_meninggal_neonatal / $total_lahir) * 1000, 1) : 0 ?>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-purple-600">
                        <?= $total_lahir > 0 ? round(($total_meninggal_bayi / $total_lahir) * 1000, 1) : 0 ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Ringkasan Statistik -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="text-center">
            <p class="text-3xl font-bold text-blue-600">
                <?= $total_lahir > 0 ? round(($total_meninggal_neonatal / $total_lahir) * 1000, 1) : 0 ?>
            </p>
            <p class="text-sm text-blue-800">AKN (‰)</p>
            <p class="text-xs text-blue-600 mt-1">Angka Kematian Neonatal</p>
        </div>
    </div>

    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
        <div class="text-center">
            <p class="text-3xl font-bold text-purple-600">
                <?= $total_lahir > 0 ? round(($total_meninggal_bayi / $total_lahir) * 1000, 1) : 0 ?>
            </p>
            <p class="text-sm text-purple-800">AKB (‰)</p>
            <p class="text-xs text-purple-600 mt-1">Angka Kematian Bayi</p>
        </div>
    </div>

    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
        <div class="text-center">
            <p class="text-3xl font-bold text-green-600">
                <?= $total_lahir > 0 ? round(($total_hidup_12_bulan / $total_lahir) * 100, 1) : 0 ?>%
            </p>
            <p class="text-sm text-green-800">Survival Rate</p>
            <p class="text-xs text-green-600 mt-1">Tingkat Kelangsungan Hidup</p>
        </div>
    </div>

    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
        <div class="text-center">
            <p class="text-3xl font-bold text-red-600">
                <?= $total_meninggal_bayi ?>
            </p>
            <p class="text-sm text-red-800">Total Meninggal</p>
            <p class="text-xs text-red-600 mt-1">Bayi Meninggal < 1 tahun</p>
        </div>
    </div>
</div>

<!-- Interpretasi -->
<div class="bg-white shadow-md rounded-lg mt-6">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Interpretasi Hasil</h3>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Angka Kematian Neonatal (AKN)</h4>
                <p class="text-sm text-gray-600 mb-4">
                    AKN adalah jumlah kematian bayi yang terjadi sebelum usia 28 hari per 1000 kelahiran hidup.
                </p>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm">Target WHO:</span>
                        <span class="font-semibold">≤ 12 ‰</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Target Kemenkes RI:</span>
                        <span class="font-semibold">≤ 15 ‰</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Hasil Klinik:</span>
                        <span class="font-semibold text-blue-600">
                            <?= $total_lahir > 0 ? round(($total_meninggal_neonatal / $total_lahir) * 1000, 1) : 0 ?> ‰
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <h4 class="font-semibold text-gray-800 mb-2">Angka Kematian Bayi (AKB)</h4>
                <p class="text-sm text-gray-600 mb-4">
                    AKB adalah jumlah kematian bayi yang terjadi sebelum usia 1 tahun per 1000 kelahiran hidup.
                </p>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-sm">Target WHO:</span>
                        <span class="font-semibold">≤ 25 ‰</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Target Kemenkes RI:</span>
                        <span class="font-semibold">≤ 24 ‰</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Hasil Klinik:</span>
                        <span class="font-semibold text-purple-600">
                            <?= $total_lahir > 0 ? round(($total_meninggal_bayi / $total_lahir) * 1000, 1) : 0 ?> ‰
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold text-gray-800 mb-2">Kesimpulan</h4>
            <p class="text-sm text-gray-600">
                <?php
                $akn = $total_lahir > 0 ? round(($total_meninggal_neonatal / $total_lahir) * 1000, 1) : 0;
                $akb = $total_lahir > 0 ? round(($total_meninggal_bayi / $total_lahir) * 1000, 1) : 0;

                if ($akn <= 15 && $akb <= 24) {
                    echo "🎉 Selamat! Klinik telah mencapai target nasional untuk AKN dan AKB.";
                } elseif ($akn <= 12 && $akb <= 25) {
                    echo "🏆 Excellent! Klinik telah mencapai target WHO untuk AKN dan AKB.";
                } else {
                    echo "⚠️ Perlu perbaikan: Klinik belum mencapai target nasional. Perlu evaluasi dan intervensi lebih lanjut.";
                }
                ?>
            </p>
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
                <p>Data kematian bayi dihitung berdasarkan asumsi standar (2% neonatal mortality). Untuk data yang lebih akurat, diperlukan sistem pencatatan kematian bayi yang terintegrasi.</p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/partials/footer.php'; ?>

<?php
// file: views/layouts/partials/sidebar.php (Final)
require_once ROOT_PATH . '/app/models/User.php';
$current_page = $_SERVER['REQUEST_URI'];
function is_active($path, $current_page) {
    if ($path === '/dashboard' && ($current_page === '/dashboard' || $current_page === '/')) { return true; }
    return strpos($current_page, $path) === 0 && $path !== '/dashboard';
}
?>
<!-- Mobile sidebar overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 md:hidden hidden"></div>

<!-- START: Sidebar -->
<div id="sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-lg border-r no-print transform -translate-x-full transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 flex flex-col">
    <div class="flex items-center justify-center h-20 border-b">
        <div class="flex items-center">
            <?php if (!empty($pengaturan['logo_klinik'])): ?>
                <img src="<?php echo e($pengaturan['logo_klinik']); ?>" alt="Logo" class="h-8 w-8 mr-3 object-cover">
            <?php else: ?>
                <img src="/assets/images/logo.png" alt="Logo" class="h-8 w-8 mr-3">
            <?php endif; ?>
            <span class="text-xl font-bold text-gray-800">ARHANSA</span>
        </div>
    </div>
    <div class="flex-1 overflow-y-auto">
        <nav class="flex-1 px-4 py-4">
            <p class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Menu</p>
            <a href="/dashboard" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/dashboard', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="layout-dashboard" class="mr-3"></i>Dashboard
            </a>
            
            <p class="mt-6 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Master Data</p>
            <a href="/pasien" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/pasien', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="users" class="mr-3"></i>Data Pasien
            </a>
            <a href="/bayi" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/bayi', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="baby" class="mr-3"></i>Data Bayi
            </a>

            <p class="mt-6 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Pelayanan</p>
            <a href="/anc" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/anc', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="heart-pulse" class="mr-3"></i>ANC
            </a>
            <a href="/inc" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/inc', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="activity" class="mr-3"></i>INC
            </a>
            <a href="/kb" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/kb', $current_page) ? 'active' : ''; ?>">
                 <i data-lucide="syringe" class="mr-3"></i>KB
            </a>
            <a href="/kunjungan-bayi" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/kunjungan-bayi', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="baby" class="mr-3"></i>Kunjungan Bayi
            </a>
            <a href="/imunisasi" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/imunisasi', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="shield-plus" class="mr-3"></i>Imunisasi
            </a>
            <a href="/nifas" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/nifas', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="clipboard-check" class="mr-3"></i>Nifas
            </a>

            <p class="mt-6 px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">Lainnya</p>
            <a href="/laporan" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/laporan', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="file-text" class="mr-3"></i>Laporan
            </a>
            <a href="/pengaturan" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/pengaturan', $current_page) ? 'active' : ''; ?>">
                <i data-lucide="settings" class="mr-3"></i>Pengaturan
            </a>
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="/pengaturan-app" class="sidebar-link mt-2 flex items-center px-4 py-2 text-gray-600 rounded-lg hover:bg-slate-100 transition-colors <?php echo is_active('/pengaturan-app', $current_page) ? 'active' : ''; ?>">
            <i data-lucide="settings-2" class="mr-3"></i>Pengaturan Aplikasi</a>
            <?php endif; ?>
        
        </nav>
            
    </div>
    <div class="px-4 py-4 border-t">
    <div class="flex items-center mb-3">
        <?php
        // Use global user data if available, otherwise get from session
        global $currentUser, $profilePicture;
        if (!isset($currentUser) || !isset($profilePicture)) {
            $currentUser = User::findById($pdo, $_SESSION['user_id']);
            $profilePicture = $currentUser['profile_picture'] ?? '/assets/images/profile.jpg';
        }
        ?>
        <img class="h-10 w-10 rounded-full object-cover" src="<?php echo e($profilePicture); ?>" alt="User profile">
        <div class="ml-3">
            <p class="text-sm font-semibold text-gray-800"><?php echo e($_SESSION['user_name'] ?? 'Pengguna'); ?></p>
            <p class="text-xs text-gray-500"><?php echo e(ucfirst($_SESSION['user_role'] ?? '')); ?></p>
        </div>
    </div>
    
    
    <!-- Tombol Profil & Logout -->
    <div class="space-y-2">
        <a href="/profile/edit" class="w-full flex items-center justify-center px-4 py-2 text-sm text-gray-700 hover:bg-slate-100 border rounded-lg transition-colors">
            <i data-lucide="user-cog" class="mr-2 h-4 w-4"></i>
            Profil Saya
        </a>
        <a href="/auth/logout" class="w-full flex items-center justify-center px-4 py-2 text-sm text-gray-700 hover:bg-red-100 hover:text-red-600 border rounded-lg transition-colors">
            <i data-lucide="log-out" class="mr-2 h-4 w-4"></i>
            Logout
        </a>
    </div>
</div>
</div>

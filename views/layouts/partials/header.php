<?php
// File: views/layouts/partials/header.php (Versi Tailwind)
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ARHANSA</title>

    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://unpkg.com/lucide@latest"></script>
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* bg-slate-50 */
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #64748b; }
        .lucide { width: 18px; height: 18px; }

        /* Style untuk menu sidebar aktif */
        .sidebar-link.active {
            background-color: #eef2ff; /* bg-indigo-100 */
            color: #4f46e5; /* text-indigo-700 */
            font-weight: 600;
        }

        /* Style untuk Cetak/Print */
        @media print {
            body {
                background-color: white;
            }
            .no-print {
                display: none !important;
            }
            .print-header {
                display: block !important;
            }
            main {
                overflow: visible !important;
            }
            .p-8 {
                padding: 0 !important;
            }
            .shadow-md {
                box-shadow: none !important;
                border: 1px solid #e5e7eb;
            }
        }
    </style>
</head>
<body class="antialiased">
    <div class="flex h-screen bg-slate-50">
        <?php 
        // Memuat Sidebar
        // Pastikan path ini benar sesuai struktur folder Anda
        include __DIR__ . '/sidebar.php'; 
        ?>

        <main class="flex-1 flex flex-col overflow-y-auto md:ml-0">
            <!-- Mobile Header with Hamburger Menu -->
            <div class="md:hidden bg-white shadow-sm border-b px-4 py-3 flex items-center justify-between no-print">
                <button id="mobile-menu-button" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500">
                    <i data-lucide="menu" class="h-6 w-6"></i>
                </button>
                <div class="flex items-center">
                    <img src="/assets/images/logo.png" alt="Logo" class="h-8 w-8 mr-2">
                    <span class="text-lg font-bold text-gray-800">PMB Modern</span>
                </div>
                <div class="w-10"></div> <!-- Spacer for centering -->
            </div>
            
            <div class="p-4 md:p-8 flex-1">

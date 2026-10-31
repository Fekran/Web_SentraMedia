<?php
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/../config/database.php';

check_login();

try {
    $stmt = $pdo->prepare("SELECT * FROM settings WHERE id = 1 LIMIT 1");
    $stmt->execute();
    $settings = $stmt->fetch();
    if (!$settings) {
        $settings = [
            'nama_toko' => 'CV. Sentra Media',
            'logo' => NULL,
            'alamat' => '',
            'telepon' => '',
            'email' => ''
        ];
    }
} catch (PDOException $e) {
    $settings = [
        'nama_toko' => 'Stok toko buku Sentra Media',
        'logo' => NULL,
        'alamat' => '',
        'telepon' => '',
        'email' => ''
    ];
}
?>
<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($settings['nama_toko']); ?> - Sistem Inventory</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/style.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="h-full text-slate-100 flex flex-col md:flex-row overflow-hidden font-sans">
    
    <?php display_flash_message(); ?>

    <div class="md:hidden flex items-center justify-between bg-slate-900 border-b border-slate-800 px-4 py-3 text-white">
        <div class="flex items-center gap-2">
            <img src="<?php echo BASE_URL; ?>assets/images/logo-sentra-media.png" alt="CV. Sentra Media" class="h-10 object-contain">
        </div>
        <button id="mobile-menu-btn" class="p-2 text-slate-400 hover:text-white transition-colors focus:outline-none">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </div>

    <?php include_once __DIR__ . '/sidebar.php'; ?>

    <div class="flex-1 flex flex-col overflow-y-auto min-w-0 bg-slate-950">

        <header class="hidden md:flex items-center justify-between px-8 py-4 bg-slate-900/40 border-b border-slate-900/80 backdrop-blur-md">
            <div>
                <h2 class="text-sm text-slate-400">Selamat datang,</h2>
                <h1 class="text-lg font-bold text-white"><?php echo e($_SESSION['nama']); ?> <span class="text-xs bg-indigo-500/20 text-indigo-400 px-2 py-0.5 rounded-full font-normal border border-indigo-500/20"><?php echo e($_SESSION['role']); ?></span></h1>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <span id="live-clock" class="text-xs font-mono text-slate-400">--:--:--</span>
                    <span class="block text-[10px] text-slate-500"><?php echo date('d M Y'); ?></span>
                </div>
                <div class="h-8 w-px bg-slate-800"></div>
                <div class="relative group">
                    <button class="flex items-center gap-2 focus:outline-none">
                        <div class="w-9 h-9 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-indigo-400 font-bold uppercase shadow-inner">
                            <?php echo substr($_SESSION['nama'], 0, 2); ?>
                        </div>
                        <i class="fas fa-chevron-down text-xs text-slate-500 group-hover:text-slate-300 transition-colors"></i>
                    </button>
                    <div class="absolute right-0 top-full w-48 bg-slate-900 border border-slate-800 rounded-xl shadow-xl py-1 hidden group-hover:block group-focus-within:block z-50">
                        <?php if ($_SESSION['role'] === 'Admin'): ?>
                        <a href="<?php echo BASE_URL; ?>pages/pengaturan.php" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-800 hover:text-white transition-colors">
                            <i class="fas fa-cog mr-2 text-slate-400"></i> Pengaturan
                        </a>
                        <?php endif; ?>
                        <a href="<?php echo BASE_URL; ?>logout.php" class="block px-4 py-2 text-sm text-rose-400 hover:bg-slate-800 hover:text-rose-300 transition-colors">
                            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 md:p-8">

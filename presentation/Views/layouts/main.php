<?php
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
$isAuthPage = strpos($requestUri, '/login') !== false || strpos($requestUri, '/register') !== false;
// Baca user dari cookie stateless (kompatibel dengan Vercel serverless)
$_authUser  = \App\Presentation\Middleware\AuthMiddleware::getUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Puskesmas Salem - Antrian & Jadwal Online') ?></title>
    
    <!-- CSS Stylesheet -->
    <link rel="stylesheet" href="<?= url('/assets/css/style.css') ?>">
    <!-- Responsive Design System (breakpoints & spacing tokens) -->
    <link rel="stylesheet" href="<?= url('/assets/css/responsive.css') ?>">
</head>
<body>

    <!-- Header / Navigation Bar (Hanya muncul jika bukan halaman login/register) -->
    <?php if (!$isAuthPage): ?>
        <header class="app-header">
            <div class="header-container">
                <a href="<?= url('/') ?>" class="logo-wrapper">
                    <div class="logo-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home" style="width: 22px; height: 22px;">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                    </div>
                    <div class="logo-text-group">
                        <span class="logo-title">Puskesmas Salem</span>
                        <span class="logo-subtitle">ANTRIAN & JADWAL ONLINE</span>
                    </div>
                </a>
                
                <!-- Burger Button -->
                <button class="burger-menu-btn" id="burgerMenuBtn" aria-label="Buka Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 26px; height: 26px;">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </header>

        <!-- Slide-out Navigation Drawer & Overlay -->
        <div class="drawer-overlay" id="drawerOverlay"></div>
        <div class="nav-drawer" id="navDrawer">
            <div class="drawer-header">
                <span class="drawer-title">Menu Navigasi</span>
                <button class="drawer-close-btn" id="drawerCloseBtn" aria-label="Tutup Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 24px; height: 24px;">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            
            <nav class="drawer-nav-links">
                <a href="<?= url('/') ?>" class="drawer-nav-item <?= $requestUri === url('/') || strpos($requestUri, '/dashboard') !== false ? 'active' : '' ?>">
                    <span>🏠</span> Beranda
                </a>
                <a href="<?= url('/kiosk') ?>" class="drawer-nav-item <?= strpos($requestUri, '/kiosk') !== false ? 'active' : '' ?>">
                    <span>➕</span> Daftar Antrian
                </a>
                <a href="<?= url('/check') ?>" class="drawer-nav-item <?= strpos($requestUri, '/check') !== false ? 'active' : '' ?>">
                    <span>🔍</span> Cek Antrian
                </a>
                <a href="<?= url('/schedule') ?>" class="drawer-nav-item <?= strpos($requestUri, '/schedule') !== false ? 'active' : '' ?>">
                    <span>📅</span> Jadwal Dokter
                </a>
                <a href="<?= url('/display') ?>" class="drawer-nav-item <?= strpos($requestUri, '/display') !== false ? 'active' : '' ?>">
                    <span>🖥️</span> Monitor Display
                </a>
                
                <?php if (\App\Presentation\Middleware\AuthMiddleware::isAuthenticated()): ?>
                    <div class="drawer-separator"></div>
                    <div class="drawer-section-title">Menu Petugas</div>
                    
                    <a href="<?= url('/queues') ?>" class="drawer-nav-item <?= strpos($requestUri, '/queues') !== false && strpos($requestUri, '/queues/history') === false ? 'active' : '' ?>">
                        <span>💻</span> Operator Panel
                    </a>
                    <a href="<?= url('/queues/history') ?>" class="drawer-nav-item <?= strpos($requestUri, '/queues/history') !== false ? 'active' : '' ?>">
                        <span>📋</span> Riwayat & Laporan
                    </a>
                    
                    <?php if (\App\Presentation\Middleware\AuthMiddleware::isAdmin()): ?>
                        <a href="<?= url('/counters') ?>" class="drawer-nav-item <?= strpos($requestUri, '/counters') !== false ? 'active' : '' ?>">
                            <span>⚙️</span> Pengaturan Loket
                        </a>
                        <a href="<?= url('/admin/master') ?>" class="drawer-nav-item <?= strpos($requestUri, '/admin/master') !== false ? 'active' : '' ?>">
                            <span>🗂️</span> Master Data
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </nav>

            <div class="drawer-footer">
                <?php if (\App\Presentation\Middleware\AuthMiddleware::isAuthenticated()): ?>
                    <div class="drawer-user-info">
                        <div class="user-badge" style="width: 100%; border-radius: 12px; margin-bottom: 12px; justify-content: flex-start; background: rgba(255,255,255,0.06); padding: 8px 12px;">
                            <span class="user-dot"></span>
                            <div style="display: flex; flex-direction: column; text-align: left;">
                                <span class="user-name" style="font-weight: 600; color: #ffffff; font-size: 0.9rem;"><?= htmlspecialchars($_authUser['name'] ?? '') ?></span>
                                <span class="user-role" style="font-size: 0.75rem; color: rgba(255,255,255,0.6);"><?= htmlspecialchars(ucfirst($_authUser['role'] ?? '')) ?></span>
                            </div>
                        </div>
                    </div>
                    <a href="<?= url('/logout') ?>" class="btn-logout-custom" style="display: block; text-align: center; text-decoration: none;">🚪 Logout</a>
                <?php else: ?>
                    <?php if (strpos($requestUri, '/login') === false && strpos($requestUri, '/register') === false): ?>
                        <a href="<?= url('/login') ?>" class="btn-admin-login" style="display: block; text-align: center; text-decoration: none;">🔓 Admin / Operator</a>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Main Container -->
    <main class="main-content-layout">
        <?= $content ?>
    </main>

    <!-- Footer (Hanya muncul jika bukan halaman login/register) -->
    <?php if (!$isAuthPage): ?>
        <footer class="footer-credits-custom" style="background-color: var(--bg-header); color: #ffffff; border-top: none;">
            <div class="footer-container">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; background: #ffffff; color: var(--bg-header); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">🏥</div>
                    <span style="font-family: var(--font-heading); font-size: 1.4rem; font-weight: 800;">Puskesmas Salem</span>
                    <span style="font-size: 0.85rem; opacity: 0.8; letter-spacing: 0.05em;">Sistem Antrian &amp; Jadwal Dokter Online</span>
                </div>
                
                <div class="footer-info-row" style="color: rgba(255,255,255,0.9);">
                    <span>📍 Jl. Desa Salem, Kecamatan Salem</span>
                    <span>✉️ puskesmassalem@gmail.com</span>
                    <span>📞 0858-6767-6760</span>
                </div>
                
                <hr style="border: none; border-top: 1px solid rgba(255,255,255,0.15); margin-bottom: 20px;">
                
                <p style="color: rgba(255,255,255,0.7); font-size: 0.82rem;">&copy; <?= date('Y') ?> Puskesmas Salem — Seluruh hak cipta dilindungi.</p>
            </div>
        </footer>
    <?php endif; ?>

    <!-- Inject dynamic base path for JS AJAX calls -->
    <script>window.APP_BASE_PATH = '<?= (isset($_SERVER['VERCEL']) || getenv('VERCEL') === '1') ? '' : rtrim(str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']), '/') ?>';</script>
    <!-- Client-Side App Script -->
    <script src="<?= url('/assets/js/app.js') ?>"></script>
</body>
</html>

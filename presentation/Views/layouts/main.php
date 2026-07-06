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
                
                <nav class="nav-links">
                    <!-- Public Links with Icons -->
                    <a href="<?= url('/') ?>" class="nav-item <?= $requestUri === url('/') || strpos($requestUri, '/dashboard') !== false ? 'active' : '' ?>">
                        <span>🏠</span> Beranda
                    </a>
                    <a href="<?= url('/kiosk') ?>" class="nav-item <?= strpos($requestUri, '/kiosk') !== false ? 'active' : '' ?>">
                        <span>➕</span> Daftar Antrian
                    </a>
                    <a href="<?= url('/check') ?>" class="nav-item <?= strpos($requestUri, '/check') !== false ? 'active' : '' ?>">
                        <span>🔍</span> Cek Antrian
                    </a>
                    <a href="<?= url('/schedule') ?>" class="nav-item <?= strpos($requestUri, '/schedule') !== false ? 'active' : '' ?>">
                        <span>📅</span> Jadwal Dokter
                    </a>
                    <a href="<?= url('/display') ?>" class="nav-item <?= strpos($requestUri, '/display') !== false ? 'active' : '' ?>">
                        <span>🖥️</span> Display
                    </a>
                    
                    <!-- Authenticated Operator/Admin Links -->
                    <?php if (\App\Presentation\Middleware\AuthMiddleware::isAuthenticated()): ?>
                        <a href="<?= url('/queues') ?>" class="nav-item <?= strpos($requestUri, '/queues') !== false && strpos($requestUri, '/queues/history') === false ? 'active' : '' ?>">
                            <span>💻</span> Operator
                        </a>
                        <a href="<?= url('/queues/history') ?>" class="nav-item <?= strpos($requestUri, '/queues/history') !== false ? 'active' : '' ?>">
                            <span>📋</span> Riwayat
                        </a>
                        
                        <?php if (\App\Presentation\Middleware\AuthMiddleware::isAdmin()): ?>
                            <a href="<?= url('/counters') ?>" class="nav-item <?= strpos($requestUri, '/counters') !== false ? 'active' : '' ?>">
                                <span>⚙️</span> Loket
                            </a>
                            <a href="<?= url('/admin/master') ?>" class="nav-item <?= strpos($requestUri, '/admin/master') !== false ? 'active' : '' ?>">
                                <span>🗂️</span> Master Data
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </nav>

                <div class="nav-auth-actions">
                    <?php if (\App\Presentation\Middleware\AuthMiddleware::isAuthenticated()): ?>
                        <div class="user-badge">
                            <span class="user-dot"></span>
                            <span class="user-name"><?= htmlspecialchars($_authUser['name'] ?? '') ?></span>
                            <span class="user-role-label"><?= htmlspecialchars(ucfirst($_authUser['role'] ?? '')) ?></span>
                        </div>
                        <a href="<?= url('/logout') ?>" class="btn-logout-custom">Logout</a>
                    <?php else: ?>
                        <?php if (strpos($requestUri, '/login') === false && strpos($requestUri, '/register') === false): ?>
                            <a href="<?= url('/login') ?>" class="btn-admin-login">🔓 Admin / Operator</a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </header>
    <?php endif; ?>

    <!-- Main Container -->
    <main class="main-content-layout">
        <?= $content ?>
    </main>

    <!-- Footer (Hanya muncul jika bukan halaman login/register) -->
    <?php if (!$isAuthPage): ?>
        <footer class="footer-credits-custom" style="background-color: var(--bg-header); color: #ffffff; border-top: none; padding: 40px 24px;">
            <div class="footer-container" style="max-width: 1200px; margin: 0 auto; text-align: center;">
                <div style="display: flex; flex-direction: column; align-items: center; gap: 12px; margin-bottom: 24px;">
                    <div style="width: 44px; height: 44px; background: #ffffff; color: var(--bg-header); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">🏥</div>
                    <span style="font-family: var(--font-heading); font-size: 1.4rem; font-weight: 800;">Puskesmas Salem</span>
                    <span style="font-size: 0.85rem; opacity: 0.8; letter-spacing: 0.05em;">Sistem Antrian & Jadwal Dokter Online</span>
                </div>
                
                <div class="flex-row" style="justify-content: center; gap: 30px; margin-bottom: 24px; font-size: 0.88rem; opacity: 0.9;">
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

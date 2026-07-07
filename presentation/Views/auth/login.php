<div class="auth-wrapper">
    <div class="glass-panel auth-card">
        <div class="auth-header">
            <div style="margin-bottom: 20px; display: flex; justify-content: center;">
                <img src="<?= url('/assets/logo.png') ?>" alt="Logo Puskesmas Salem" style="height: 70px; width: auto; object-fit: contain;">
            </div>
            <h2 style="font-size: 1.4rem; font-weight: 800; margin-bottom: 6px; color: var(--text-primary); line-height: 1.3;">Sistem Informasi Antrian Pasien</h2>
            <p style="color: var(--primary-dark); font-size: 1.1rem; font-weight: 700; margin-bottom: 0;">Puskesmas Salem</p>
        </div>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger">
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <span><?= htmlspecialchars($success) ?></span>
            </div>
        <?php endif; ?>

        <form action="<?= url('/login') ?>" method="POST" autocomplete="off">
            <div class="form-group">
                <label for="email" class="form-label">Alamat Email</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="nama@email.com" required autofocus>
            </div>

            <div class="form-group" style="margin-bottom: 24px;">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block">Masuk Sekarang</button>
        </form>

        <div class="auth-footer">
            Kembali ke <a href="<?= url('/') ?>" class="auth-link">Beranda</a>
        </div>
    </div>
</div>

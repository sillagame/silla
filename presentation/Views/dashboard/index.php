<!-- Hero Section (Clean green gradient) -->
<div class="hero-section">
    <div class="hero-container-inner">
        <div class="system-status">
            <span class="status-dot"></span> Sistem Online Aktif
        </div>
        
        <h1 class="hero-title">
            Antrian <em>Puskesmas</em><br>
            Lebih Mudah
        </h1>
        
        <p class="hero-desc">
            Daftar antrian dan pantau jadwal dokter tanpa harus mengantri lama di loket. Hemat waktu, layanan lebih nyaman.
        </p>
        
        <div class="hero-actions">
            <a href="<?= url('/kiosk') ?>" class="btn-hero-primary">
                <span>➕</span> Daftar Antrian Sekarang
            </a>
            <a href="<?= url('/check') ?>" class="btn-hero-secondary">
                <span>🔍</span> Cek Status Antrian
            </a>
        </div>
    </div>
</div>

<!-- Stats Counter Bar -->
<div class="stats-bar">
    <div class="stats-bar-container">
        <div class="stat-item">
            <span class="stat-num"><?= $stats['total'] ?></span>
            <span class="stat-label">Antrian Hari Ini</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" style="color: var(--warning);"><?= $stats['waiting'] ?></span>
            <span class="stat-label">Menunggu Dipanggil</span>
        </div>
        <div class="stat-item">
            <span class="stat-num" style="color: var(--success);"><?= $stats['dokters'] ?></span>
            <span class="stat-label">Dokter Aktif</span>
        </div>
    </div>
</div>

<div class="page-container">
    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <span><strong>Sukses!</strong> <?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <span><strong>Error:</strong> <?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <!-- Jadwal Hari Ini Section -->
    <div class="section-spacing">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 class="section-title" style="margin-bottom: 0;">Jadwal Hari Ini</h2>
            <a href="<?= url('/schedule') ?>" style="color: var(--primary); text-decoration: none; font-weight: 700; font-size: 0.95rem;">Lihat semua &rarr;</a>
        </div>

        <?php if (empty($jadwalHariIni)): ?>
            <div class="glass-panel" style="text-align: center; padding: 40px 20px;">
                <span style="font-size: 3rem;">📅</span>
                <h3 style="margin-top: 16px; margin-bottom: 8px;">Tidak Ada Jadwal Praktik</h3>
                <p style="color: var(--text-secondary);">Mohon maaf, tidak ada jadwal dokter praktik pada hari <?= $hariIni ?> ini.</p>
            </div>
        <?php else: ?>
            <div class="grid-2">
                <?php foreach ($jadwalHariIni as $j): ?>
                    <div class="glass-panel" style="padding: 24px; display: flex; justify-content: space-between; align-items: center; border-left: 6px solid var(--primary); border-radius: 12px; transition: var(--transition-smooth);" onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='none'">
                        <div>
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 12px;">
                                <div style="width: 40px; height: 40px; background: #f0fdf4; color: var(--primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; font-weight: bold;">
                                    <?= strtoupper(substr($j['nama_dokter'], 4, 2)) ?>
                                </div>
                                <div>
                                    <h4 style="font-size: 1.05rem; margin-bottom: 2px; color: var(--text-primary);"><?= htmlspecialchars($j['nama_dokter']) ?></h4>
                                    <span style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;"><?= htmlspecialchars($j['nama_poli']) ?></span>
                                </div>
                            </div>
                            <div style="font-size: 0.88rem; color: var(--text-secondary); display: flex; flex-direction: column; gap: 4px;">
                                <span>🕒 <?= $j['jam'] ?> WIB</span>
                                <span style="font-weight: 600;">Sisa Kuota: <strong style="color: <?= $j['sisa'] > 0 ? 'var(--primary-dark)' : 'var(--danger)' ?>;"><?= $j['sisa'] ?></strong> / <?= $j['kuota'] ?></span>
                            </div>
                        </div>
                        <div>
                            <?php if ($j['sisa'] > 0): ?>
                                <a href="<?= url('/kiosk?kd_poli=' . $j['kd_poli'] . '&kd_dokter=' . $j['kd_dokter']) ?>" class="btn" style="padding: 10px 20px; font-size: 0.88rem; background-color: #f0fdf4; color: var(--primary-dark); border: 1.5px solid var(--primary-glow); font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-block; transition: var(--transition-smooth);" onmouseover="this.style.backgroundColor='var(--primary)'; this.style.color='#ffffff';" onmouseout="this.style.backgroundColor='#f0fdf4'; this.style.color='var(--primary-dark)';">
                                    Daftar
                                </a>
                            <?php else: ?>
                                <button class="btn" style="padding: 10px 20px; font-size: 0.88rem; background-color: #f1f5f9; color: var(--text-muted); border: 1.5px solid #cbd5e1; font-weight: 700; border-radius: 8px; cursor: not-allowed;" disabled>
                                    Penuh
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Cara Daftar Antrian Section -->
    <div class="section-spacing" style="background-color: #f8fafc; border-radius: 20px; padding: 40px var(--container-px); border: 1px solid var(--border-light);">
        <h2 class="section-title" style="text-align: center; margin-bottom: 40px; font-family: var(--font-heading);">Cara Daftar Antrian</h2>
        
        <div class="grid-3" style="gap: 30px;">
            <!-- Langkah 1 -->
            <div style="text-align: center; display: flex; flex-direction: column; align-items: center;">
                <div style="width: 50px; height: 50px; background-color: #e0f2fe; color: #0284c7; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(2, 132, 199, 0.15);">
                    01
                </div>
                <h4 style="font-size: 1.05rem; margin-bottom: 10px; font-family: var(--font-heading);">Isi Formulir</h4>
                <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.5; max-width: 250px;">
                    Masukkan data diri dan pilih poli serta dokter yang dituju.
                </p>
            </div>

            <!-- Langkah 2 -->
            <div style="text-align: center; display: flex; flex-direction: column; align-items: center;">
                <div style="width: 50px; height: 50px; background-color: #dcfce7; color: #16a34a; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(22, 163, 74, 0.15);">
                    02
                </div>
                <h4 style="font-size: 1.05rem; margin-bottom: 10px; font-family: var(--font-heading);">Dapatkan Kode</h4>
                <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.5; max-width: 250px;">
                    Sistem memberikan kode antrian unik sebagai bukti pendaftaran Anda.
                </p>
            </div>

            <!-- Langkah 3 -->
            <div style="text-align: center; display: flex; flex-direction: column; align-items: center;">
                <div style="width: 50px; height: 50px; background-color: #fef3c7; color: #d97706; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(217, 119, 6, 0.15);">
                    03
                </div>
                <h4 style="font-size: 1.05rem; margin-bottom: 10px; font-family: var(--font-heading);">Pantau Antrian</h4>
                <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.5; max-width: 250px;">
                    Cek status antrian kapan saja menggunakan kode yang didapat.
                </p>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 40px; display: flex; justify-content: center;">
            <!-- Langkah 4 -->
            <div style="text-align: center; display: flex; flex-direction: column; align-items: center;">
                <div style="width: 50px; height: 50px; background-color: #f3e8ff; color: #9333ea; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; font-weight: bold; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(147, 51, 234, 0.15);">
                    04
                </div>
                <h4 style="font-size: 1.05rem; margin-bottom: 10px; font-family: var(--font-heading);">Datang ke Puskesmas</h4>
                <p style="font-size: 0.88rem; color: var(--text-secondary); line-height: 1.5; max-width: 250px;">
                    Tunjukkan kode antrian saat dipanggil petugas di loket.
                </p>
            </div>
        </div>
    </div>
</div>

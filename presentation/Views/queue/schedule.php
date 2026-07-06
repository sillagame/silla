<!-- Halaman Jadwal Dokter -->
<div class="page-container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div>
            <h1 style="font-size: 2.2rem; margin-bottom: 5px; font-family: var(--font-heading);">🗓️ Jadwal Dokter</h1>
            <p style="color: var(--text-secondary); font-size: 0.95rem;">Jadwal praktik dokter di semua poliklinik Puskesmas Salem.</p>
        </div>
        <a href="<?= url('/kiosk') ?>" class="btn-hero-primary" style="padding: 12px 24px; font-size: 0.9rem;">
            <span>➕</span> Daftar Antrian
        </a>
    </div>

    <!-- Grouped by Day -->
    <?php 
    $daftarHari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    foreach ($daftarHari as $hari): 
        $jadwalHari = $jadwalPerHari[$hari] ?? [];
    ?>
        <div style="margin-bottom: 35px;">
            <h3 style="font-size: 1.25rem; font-family: var(--font-heading); color: var(--bg-header); border-bottom: 2px solid var(--border-light); padding-bottom: 8px; margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                <span>📅</span> Hari <?= $hari ?>
            </h3>
            
            <?php if (empty($jadwalHari)): ?>
                <p style="color: var(--text-muted); font-style: italic; font-size: 0.9rem; margin-left: 28px;">Tidak ada jadwal praktik.</p>
            <?php else: ?>
                <div class="grid-2" style="gap: 16px; padding-left: 12px;">
                    <?php foreach ($jadwalHari as $j): ?>
                        <div class="glass-panel" style="padding: 20px; display: flex; justify-content: space-between; align-items: center; border-radius: 12px; transition: var(--transition-smooth);" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='none'">
                            <div>
                                <h4 style="font-size: 1.05rem; margin-bottom: 4px; color: var(--text-primary);"><?= htmlspecialchars($j['nama_dokter']) ?></h4>
                                <div style="display: flex; gap: 8px; align-items: center; margin-bottom: 8px;">
                                    <span style="font-size: 0.75rem; background-color: #f0fdf4; color: var(--primary-dark); font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: uppercase;">
                                        <?= htmlspecialchars($j['nama_poli']) ?>
                                    </span>
                                </div>
                                <div style="font-size: 0.88rem; color: var(--text-secondary); display: flex; flex-direction: column; gap: 2px;">
                                    <span>🕒 Jam: <?= substr($j['jam_mulai'], 0, 5) ?> - <?= substr($j['jam_selesai'], 0, 5) ?> WIB</span>
                                    <span>👥 Kuota: <strong><?= $j['kuota'] ?></strong> pasien</span>
                                </div>
                            </div>
                            <div>
                                <a href="<?= url('/kiosk?kd_poli=' . $j['kd_poli'] . '&kd_dokter=' . $j['kd_dokter']) ?>" class="btn" style="padding: 8px 16px; font-size: 0.85rem; background-color: var(--primary); color: #ffffff; border: none; font-weight: 700; border-radius: 8px; text-decoration: none; display: inline-block; transition: var(--transition-smooth);" onmouseover="this.style.backgroundColor='var(--primary-dark)'" onmouseout="this.style.backgroundColor='var(--primary)'">
                                    Daftar Sekarang
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

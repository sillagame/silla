<!-- Halaman Cek Status Antrian -->
<div class="auth-wrapper" style="min-height: calc(100vh - 300px); padding: 40px 16px;">
    <div style="width: 100%; max-width: 500px; display: flex; flex-direction: column; gap: 24px;">
        
        <!-- Search Card -->
        <div class="glass-panel" style="padding: 0; overflow: hidden; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.06);">
            <!-- Card Header -->
            <div style="background-color: var(--bg-header); color: #ffffff; padding: 20px 24px; display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 1.5rem;">🔍</span>
                <h2 style="color: #ffffff; margin: 0; font-family: var(--font-heading); font-size: 1.35rem;">Cek Status Antrian</h2>
            </div>
            
            <!-- Card Body -->
            <div style="padding: 30px 24px;">
                <form action="<?= url('/check') ?>" method="GET">
                    <div class="form-group" style="margin-bottom: 24px;">
                        <label for="code" class="form-label" style="font-size: 0.85rem; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; display: block;">Masukkan Kode Antrian</label>
                        <input type="text" id="code" name="code" class="form-control" placeholder="Contoh: A001-060726" value="<?= htmlspecialchars($searchCode ?? '') ?>" required style="text-align: center; font-size: 1.3rem; font-weight: 700; letter-spacing: 0.05em; padding: 14px; text-transform: uppercase;" autofocus>
                        <span style="display: block; margin-top: 10px; font-size: 0.78rem; color: var(--text-muted); text-align: center;">
                            Kode antrian terdiri dari huruf + angka (contoh: A001-060726)
                        </span>
                    </div>
                    
                    <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; background: var(--primary); color: #ffffff; border: none; border-radius: 10px; font-size: 1rem; font-weight: 700; font-family: var(--font-heading); cursor: pointer; transition: var(--transition-smooth); display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <span>🔍</span> Cek Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Search Result Card -->
        <?php if ($searched): ?>
            <?php if ($queue): ?>
                <div class="glass-panel" style="padding: 30px; border-radius: 16px; border-left: 6px solid <?= $queue['status'] === 'Selesai' ? 'var(--success)' : ($queue['status'] === 'Dipanggil' ? 'var(--warning)' : 'var(--primary)') ?>; animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                    <h3 style="font-family: var(--font-heading); margin-bottom: 16px; border-bottom: 1px solid var(--border-light); padding-bottom: 10px; font-size: 1.15rem;">
                        📋 Hasil Pencarian Antrian
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 14px;">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.9rem; color: var(--text-secondary);">Kode Tiket:</span>
                            <strong style="font-size: 1rem; color: var(--text-primary); font-family: var(--font-heading);"><?= htmlspecialchars($queue['id']) ?></strong>
                        </div>
                        
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.9rem; color: var(--text-secondary);">Nama Pasien:</span>
                            <strong style="font-size: 1rem; color: var(--text-primary);"><?= htmlspecialchars($queue['nama_pasien']) ?></strong>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.9rem; color: var(--text-secondary);">Poliklinik Tujuan:</span>
                            <span style="font-size: 0.88rem; background-color: #f0fdf4; color: var(--primary-dark); font-weight: 700; padding: 3px 10px; border-radius: 6px; text-transform: uppercase;">
                                <?= htmlspecialchars($queue['nama_poli']) ?>
                            </span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.9rem; color: var(--text-secondary);">Dokter Pemeriksa:</span>
                            <strong style="font-size: 0.95rem; color: var(--text-primary);"><?= htmlspecialchars($queue['nama_dokter']) ?></strong>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.9rem; color: var(--text-secondary);">Tanggal Kunjungan:</span>
                            <strong style="font-size: 0.95rem; color: var(--text-primary);"><?= date('d F Y', strtotime($queue['tanggal'])) ?></strong>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-size: 0.9rem; color: var(--text-secondary);">Jenis Pembayaran:</span>
                            <strong style="font-size: 0.95rem; color: var(--text-primary);"><?= htmlspecialchars($queue['jenis_pembayaran']) ?></strong>
                        </div>

                        <hr style="border: none; border-top: 1px solid var(--border-light); margin: 6px 0;">

                        <div style="text-align: center; padding: 12px; background-color: #f8fafc; border-radius: 12px; border: 1px solid var(--border-light);">
                            <span style="font-size: 0.8rem; color: var(--text-secondary); display: block; margin-bottom: 4px; font-weight: 700; text-transform: uppercase;">Nomor Antrian Anda</span>
                            <strong style="font-size: 2.4rem; color: var(--primary-dark); font-family: var(--font-heading); display: block; line-height: 1.1;"><?= htmlspecialchars($queue['queue_number']) ?></strong>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px 16px; background-color: <?= $queue['status'] === 'Selesai' ? '#f0fdf4' : ($queue['status'] === 'Dipanggil' ? '#fffbeb' : '#eff6ff') ?>; border-radius: 10px;">
                            <span style="font-size: 0.9rem; color: var(--text-secondary); font-weight: 600;">Status Antrian:</span>
                            <strong style="font-size: 1rem; color: <?= $queue['status'] === 'Selesai' ? 'var(--success)' : ($queue['status'] === 'Dipanggil' ? 'var(--warning)' : '#1d4ed8') ?>; font-family: var(--font-heading); text-transform: uppercase;">
                                <?= htmlspecialchars($queue['status']) ?>
                            </strong>
                        </div>

                        <?php if ($queue['status'] === 'Menunggu'): ?>
                            <div style="text-align: center; font-size: 0.85rem; color: var(--text-secondary); line-height: 1.5; padding: 0 10px;">
                                Silakan pantau terus antrian Anda. Harap hadir di Puskesmas Salem paling lambat 15 menit sebelum perkiraan jam pelayanan Anda.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="glass-panel" style="padding: 24px; border-radius: 16px; border-left: 6px solid var(--danger); text-align: center; animation: slideDown 0.4s cubic-bezier(0.4, 0, 0.2, 1);">
                    <span style="font-size: 2.2rem;">⚠️</span>
                    <h4 style="font-family: var(--font-heading); margin-top: 10px; margin-bottom: 6px; color: #b91c1c;">Kode Antrian Tidak Ditemukan</h4>
                    <p style="color: var(--text-secondary); font-size: 0.88rem; margin-bottom: 0;">
                        Pastikan kode antrian yang Anda masukkan sudah benar (termasuk huruf kapital dan tanda hubung).
                    </p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
    </div>
</div>

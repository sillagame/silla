<!-- Halaman Control Panel Operator -->
<div class="page-container">
    <div class="page-header">
        <div class="page-header-title">
            <h1>💻 Operator Control Panel</h1>
            <p>Kelola pemanggilan dan pelayanan antrian pasien.</p>
        </div>
    </div>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success">
            <span><?= htmlspecialchars($success) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <span><?= htmlspecialchars($error) ?></span>
        </div>
    <?php endif; ?>

    <?php if (!$activeCounter): ?>
        <!-- Mode Pilih Loket Tugas -->
        <div class="glass-panel" style="max-width: 500px; margin: 40px auto;">
            <h2 class="glass-panel-title" style="text-align: center; font-family: var(--font-heading);">Pilih Loket Tugas</h2>
            <p style="color: var(--text-secondary); text-align: center; margin-bottom: 24px;">
                Silakan pilih loket pelayanan tempat Anda bertugas saat ini.
            </p>
            
            <form action="<?= url('/queues/select-counter') ?>" method="POST">
                <div class="form-group">
                    <label for="counter_id" class="form-label">Daftar Loket Aktif</label>
                    <select name="counter_id" id="counter_id" class="form-control" required style="height: 48px; border-radius: 10px;">
                        <option value="">-- Pilih Loket --</option>
                        <?php foreach ($counters as $c): ?>
                            <?php if ($c['is_active']): ?>
                                <option value="<?= htmlspecialchars($c['id']) ?>"><?= htmlspecialchars($c['name']) ?></option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 24px; padding: 14px; border: none; font-weight: 700; border-radius: 10px;">
                    Aktifkan Loket
                </button>
            </form>
        </div>
    <?php else: ?>
        <!-- Mode Control Panel Aktif -->
        <div class="operator-layout">
            
            <!-- Kolom Kiri: Panggilan & Kontrol -->
            <div>
                <div class="glass-panel" style="padding: 30px; margin-bottom: 24px; text-align: center; border-radius: 16px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-light); padding-bottom: 15px; margin-bottom: 24px;">
                        <div style="text-align: left;">
                            <span style="font-size: 0.75rem; background-color: #eff6ff; color: #1d4ed8; font-weight: 700; padding: 3px 10px; border-radius: 30px; text-transform: uppercase;">
                                Sedang Bertugas
                            </span>
                            <h3 style="font-size: 1.3rem; margin-top: 6px; font-family: var(--font-heading);"><?= htmlspecialchars($activeCounter['name']) ?></h3>
                        </div>
                        <form action="<?= url('/queues/select-counter') ?>" method="POST">
                            <input type="hidden" name="counter_id" value="">
                            <button type="submit" class="btn" style="padding: 8px 16px; font-size: 0.82rem; background-color: #f1f5f9; color: var(--text-primary); border: 1.5px solid var(--border-light); font-weight: 700; border-radius: 8px; cursor: pointer;">
                                Ganti Loket
                            </button>
                        </form>
                    </div>

                    <?php if ($currentQueue): ?>
                        <!-- Sedang Melayani Seseorang -->
                        <span style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Sedang Dilayani</span>
                        <div style="font-size: 4rem; color: var(--primary-dark); font-family: var(--font-heading); font-weight: 800; line-height: 1; margin-bottom: 12px;">
                            <?= htmlspecialchars($currentQueue['queue_number']) ?>
                        </div>
                        
                        <div style="background-color: #f8fafc; padding: 16px; border-radius: 12px; border: 1px solid var(--border-light); margin-bottom: 24px; text-align: left; font-size: 0.9rem; display: flex; flex-direction: column; gap: 8px;">
                            <div><span style="color: var(--text-secondary);">Nama Pasien:</span> <strong><?= htmlspecialchars($currentQueue['nama_pasien']) ?></strong></div>
                            <div><span style="color: var(--text-secondary);">Kode Pendaftaran:</span> <strong><?= htmlspecialchars($currentQueue['id']) ?></strong></div>
                            <div><span style="color: var(--text-secondary);">Jenis Bayar:</span> <strong><?= htmlspecialchars($currentQueue['jenis_pembayaran']) ?></strong></div>
                            <?php if (!empty($currentQueue['keluhan'])): ?>
                                <div><span style="color: var(--text-secondary);">Keluhan:</span> <em style="color: var(--text-muted); font-size: 0.85rem;">"<?= htmlspecialchars($currentQueue['keluhan']) ?>"</em></div>
                            <?php endif; ?>
                        </div>

                        <div class="flex-row" style="gap: 10px; justify-content: center;">
                            <form action="<?= url('/queues/complete') ?>" method="POST" style="flex: 1; min-width: 0;">
                                <input type="hidden" name="queue_id" value="<?= htmlspecialchars($currentQueue['id']) ?>">
                                <button type="submit" class="btn btn-success" style="width: 100%;">
                                    ✅ Selesai Melayani
                                </button>
                            </form>
                            <form action="<?= url('/queues/skip') ?>" method="POST" style="flex: 1; min-width: 0;">
                                <input type="hidden" name="queue_id" value="<?= htmlspecialchars($currentQueue['id']) ?>">
                                <button type="submit" class="btn btn-danger" style="width: 100%;">
                                    ⏭ Lewati
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <!-- Loket Kosong -->
                        <span style="font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em; display: block; margin-bottom: 4px;">Status Loket</span>
                        <div style="font-size: 4rem; color: var(--text-muted); font-family: var(--font-heading); font-weight: 800; line-height: 1; margin-bottom: 12px;">OFF</div>
                        <p style="color: var(--text-secondary); margin-bottom: 24px;">
                            Silakan panggil antrian berikutnya jika sudah siap.
                        </p>

                        <form action="<?= url('/queues/call') ?>" method="POST" style="max-width: 300px; margin: 0 auto;">
                            <button type="submit" class="btn-primary" style="width: 100%; padding: 14px 28px; font-size: 1.05rem;">
                                Panggil Antrian Berikutnya
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                
                <?php if ($currentQueue): ?>
                    <!-- Tombol Panggil Berikutnya ketika Sedang Melayani -->
                    <div class="glass-panel" style="text-align: center; border-radius: 16px; padding: 20px;">
                        <p style="color: var(--text-secondary); margin-bottom: 12px; font-size: 0.88rem;">
                            Ingin langsung memanggil antrian berikutnya? Antrian saat ini otomatis ditandai <strong>selesai</strong>.
                        </p>
                        <form action="<?= url('/queues/call') ?>" method="POST" style="max-width: 300px; margin: 0 auto;">
                            <button type="submit" class="btn-primary" style="width: 100%; padding: 12px;">
                                Panggil Antrian Selanjutnya
                            </button>
                        </form>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kolom Kanan: Daftar Antrian Menunggu -->
            <div>
                <div class="glass-panel" style="height: 100%; display: flex; flex-direction: column; border-radius: 16px; padding: 24px;">
                    <h3 class="glass-panel-title" style="margin-bottom: 15px; font-size: 1.15rem; font-family: var(--font-heading);">
                        Daftar Tunggu (<?= count($waitingQueues) ?>)
                    </h3>
                    
                    <div style="flex: 1; overflow-y: auto; max-height: 480px; padding-right: 5px;">
                        <?php if (empty($waitingQueues)): ?>
                            <p style="color: var(--text-muted); text-align: center; padding: 40px 0; font-size: 0.9rem;">
                                Tidak ada antrian menunggu.
                            </p>
                        <?php else: ?>
                            <div style="display: flex; flex-direction: column; gap: 10px;">
                                <?php foreach ($waitingQueues as $wq): ?>
                                    <div style="display: flex; justify-content: space-between; align-items: center; background: #f8fafc; border: 1px solid var(--border-light); padding: 12px 14px; border-radius: 10px;">
                                        <div>
                                            <div style="font-weight: 800; font-size: 1.05rem; color: var(--primary-dark); font-family: var(--font-heading);"><?= htmlspecialchars($wq['queue_number']) ?></div>
                                            <div style="font-size: 0.82rem; font-weight: 600; color: var(--text-primary); margin-top: 2px;"><?= htmlspecialchars($wq['nama_pasien']) ?></div>
                                            <span style="font-size: 0.72rem; color: var(--text-muted);"><?= substr($wq['jam_ambil'], 0, 5) ?> WIB</span>
                                        </div>
                                        <span class="badge badge-waiting" style="font-size: 0.7rem; padding: 3px 8px;">Menunggu</span>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

        </div>
    <?php endif; ?>
</div>

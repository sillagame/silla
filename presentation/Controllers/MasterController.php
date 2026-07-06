<?php

namespace App\Presentation\Controllers;

use Container;
use PDO;
use Exception;
use App\Presentation\Middleware\AuthMiddleware;

class MasterController extends Controller
{
    /**
     * Tampilan utama kelola master data (Admin Only)
     */
    public function index(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        // Ambil data Poliklinik (Poli)
        $polis = $db->query("SELECT * FROM polis ORDER BY kd_poli ASC")->fetchAll(PDO::FETCH_ASSOC);

        // Ambil data Dokter
        $dokters = $db->query("
            SELECT d.*, p.nama_poli 
            FROM dokters d 
            JOIN polis p ON d.kd_poli = p.kd_poli 
            ORDER BY d.kd_dokter ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Ambil data Jadwal Praktik
        $jadwals = $db->query("
            SELECT jp.*, d.nama_dokter, p.nama_poli 
            FROM jadwal_praktiks jp 
            JOIN dokters d ON jp.kd_dokter = d.kd_dokter 
            JOIN polis p ON d.kd_poli = p.kd_poli 
            ORDER BY FIELD(jp.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jp.jam_mulai ASC
        ")->fetchAll(PDO::FETCH_ASSOC);

        // Ambil data Pasien (Batasi 100 terakhir agar cepat)
        $pasiens = $db->query("SELECT * FROM pasiens ORDER BY created_at DESC LIMIT 100")->fetchAll(PDO::FETCH_ASSOC);

        $success = $_GET['success'] ?? null;
        $error   = $_GET['error']   ?? null;
        $activeTab = $_GET['tab'] ?? 'poli'; // default tab

        $this->render('counter/master', [
            'title'     => 'Manajemen Master Data - SiLLA',
            'polis'     => $polis,
            'dokters'   => $dokters,
            'jadwals'   => $jadwals,
            'pasiens'   => $pasiens,
            'success'   => $success ? htmlspecialchars(urldecode($success)) : null,
            'error'     => $error ? htmlspecialchars(urldecode($error)) : null,
            'activeTab' => $activeTab
        ]);
    }

    // =================================================================
    // CRUD POLIKLINIK
    // =================================================================

    public function createPoli(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $kd_poli   = trim($_POST['kd_poli'] ?? '');
        $nama_poli = trim($_POST['nama_poli'] ?? '');

        try {
            $stmt = $db->prepare("INSERT INTO polis (kd_poli, nama_poli) VALUES (:kd_poli, :nama_poli)");
            $stmt->execute(['kd_poli' => $kd_poli, 'nama_poli' => $nama_poli]);
            $this->redirect('/admin/master?tab=poli&success=' . urlencode('Poli berhasil ditambahkan.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=poli&error=' . urlencode($e->getMessage()));
        }
    }

    public function updatePoli(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $kd_poli   = trim($_POST['kd_poli'] ?? '');
        $nama_poli = trim($_POST['nama_poli'] ?? '');

        try {
            $stmt = $db->prepare("UPDATE polis SET nama_poli = :nama_poli WHERE kd_poli = :kd_poli");
            $stmt->execute(['kd_poli' => $kd_poli, 'nama_poli' => $nama_poli]);
            $this->redirect('/admin/master?tab=poli&success=' . urlencode('Poli berhasil diperbarui.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=poli&error=' . urlencode($e->getMessage()));
        }
    }

    public function deletePoli(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $kd_poli = $_POST['kd_poli'] ?? '';

        try {
            $stmt = $db->prepare("DELETE FROM polis WHERE kd_poli = :kd_poli");
            $stmt->execute(['kd_poli' => $kd_poli]);
            $this->redirect('/admin/master?tab=poli&success=' . urlencode('Poli berhasil dihapus.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=poli&error=' . urlencode($e->getMessage()));
        }
    }

    // =================================================================
    // CRUD DOKTER
    // =================================================================

    public function createDokter(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $kd_dokter   = trim($_POST['kd_dokter'] ?? '');
        $nama_dokter = trim($_POST['nama_dokter'] ?? '');
        $kd_poli     = $_POST['kd_poli'] ?? '';

        try {
            $stmt = $db->prepare("INSERT INTO dokters (kd_dokter, nama_dokter, kd_poli) VALUES (:kd_dokter, :nama_dokter, :kd_poli)");
            $stmt->execute(['kd_dokter' => $kd_dokter, 'nama_dokter' => $nama_dokter, 'kd_poli' => $kd_poli]);
            $this->redirect('/admin/master?tab=dokter&success=' . urlencode('Dokter berhasil ditambahkan.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=dokter&error=' . urlencode($e->getMessage()));
        }
    }

    public function updateDokter(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $kd_dokter   = trim($_POST['kd_dokter'] ?? '');
        $nama_dokter = trim($_POST['nama_dokter'] ?? '');
        $kd_poli     = $_POST['kd_poli'] ?? '';

        try {
            $stmt = $db->prepare("UPDATE dokters SET nama_dokter = :nama_dokter, kd_poli = :kd_poli WHERE kd_dokter = :kd_dokter");
            $stmt->execute(['kd_dokter' => $kd_dokter, 'nama_dokter' => $nama_dokter, 'kd_poli' => $kd_poli]);
            $this->redirect('/admin/master?tab=dokter&success=' . urlencode('Dokter berhasil diperbarui.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=dokter&error=' . urlencode($e->getMessage()));
        }
    }

    public function deleteDokter(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $kd_dokter = $_POST['kd_dokter'] ?? '';

        try {
            $stmt = $db->prepare("DELETE FROM dokters WHERE kd_dokter = :kd_dokter");
            $stmt->execute(['kd_dokter' => $kd_dokter]);
            $this->redirect('/admin/master?tab=dokter&success=' . urlencode('Dokter berhasil dihapus.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=dokter&error=' . urlencode($e->getMessage()));
        }
    }

    // =================================================================
    // CRUD JADWAL PRAKTIK
    // =================================================================

    public function createJadwal(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $kd_dokter   = $_POST['kd_dokter'] ?? '';
        $hari        = $_POST['hari'] ?? 'Senin';
        $jam_mulai   = $_POST['jam_mulai'] ?? '08:00';
        $jam_selesai = $_POST['jam_selesai'] ?? '12:00';
        $kuota       = (int) ($_POST['kuota'] ?? 20);

        try {
            $stmt = $db->prepare("
                INSERT INTO jadwal_praktiks (kd_dokter, hari, jam_mulai, jam_selesai, kuota) 
                VALUES (:kd_dokter, :hari, :jam_mulai, :jam_selesai, :kuota)
            ");
            $stmt->execute([
                'kd_dokter'   => $kd_dokter,
                'hari'        => $hari,
                'jam_mulai'   => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'kuota'       => $kuota
            ]);
            $this->redirect('/admin/master?tab=jadwal&success=' . urlencode('Jadwal praktik berhasil ditambahkan.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=jadwal&error=' . urlencode($e->getMessage()));
        }
    }

    public function updateJadwal(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $id          = $_POST['id'] ?? '';
        $kd_dokter   = $_POST['kd_dokter'] ?? '';
        $hari        = $_POST['hari'] ?? 'Senin';
        $jam_mulai   = $_POST['jam_mulai'] ?? '08:00';
        $jam_selesai = $_POST['jam_selesai'] ?? '12:00';
        $kuota       = (int) ($_POST['kuota'] ?? 20);

        try {
            $stmt = $db->prepare("
                UPDATE jadwal_praktiks 
                SET kd_dokter = :kd_dokter, hari = :hari, jam_mulai = :jam_mulai, jam_selesai = :jam_selesai, kuota = :kuota
                WHERE id = :id
            ");
            $stmt->execute([
                'kd_dokter'   => $kd_dokter,
                'hari'        => $hari,
                'jam_mulai'   => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                'kuota'       => $kuota,
                'id'          => $id
            ]);
            $this->redirect('/admin/master?tab=jadwal&success=' . urlencode('Jadwal praktik berhasil diperbarui.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=jadwal&error=' . urlencode($e->getMessage()));
        }
    }

    public function deleteJadwal(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $id = $_POST['id'] ?? '';

        try {
            $stmt = $db->prepare("DELETE FROM jadwal_praktiks WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $this->redirect('/admin/master?tab=jadwal&success=' . urlencode('Jadwal praktik berhasil dihapus.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=jadwal&error=' . urlencode($e->getMessage()));
        }
    }

    // =================================================================
    // CRUD PASIEN
    // =================================================================

    public function createPasien(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $nik       = trim($_POST['nik'] ?? '');
        $nama      = trim($_POST['nama'] ?? '');
        $tgl_lahir = $_POST['tgl_lahir'] ?? '';
        $jk        = $_POST['jk'] ?? '';
        $no_telp   = trim($_POST['no_telp'] ?? '');
        $no_bpjs   = trim($_POST['no_bpjs'] ?? '');
        $alamat    = trim($_POST['alamat'] ?? '');

        try {
            if (strlen($nik) !== 16) {
                throw new Exception('NIK harus berjumlah 16 digit.');
            }
            $no_rm = 'RM' . str_pad((string) rand(1, 999999), 6, '0', STR_PAD_LEFT);
            
            $stmt = $db->prepare("
                INSERT INTO pasiens (no_rm, nik, nama, tgl_lahir, jk, no_telp, no_bpjs, alamat, created_at)
                VALUES (:no_rm, :nik, :nama, :tgl_lahir, :jk, :no_telp, :no_bpjs, :alamat, CURRENT_TIMESTAMP)
            ");
            $stmt->execute([
                'no_rm'     => $no_rm,
                'nik'       => $nik,
                'nama'      => $nama,
                'tgl_lahir' => $tgl_lahir,
                'jk'        => $jk,
                'no_telp'   => $no_telp,
                'no_bpjs'   => $no_bpjs ?: null,
                'alamat'    => $alamat
            ]);
            $this->redirect('/admin/master?tab=pasien&success=' . urlencode('Data Pasien berhasil dibuat.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=pasien&error=' . urlencode($e->getMessage()));
        }
    }

    public function updatePasien(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $no_rm     = $_POST['no_rm'] ?? '';
        $nik       = trim($_POST['nik'] ?? '');
        $nama      = trim($_POST['nama'] ?? '');
        $tgl_lahir = $_POST['tgl_lahir'] ?? '';
        $jk        = $_POST['jk'] ?? '';
        $no_telp   = trim($_POST['no_telp'] ?? '');
        $no_bpjs   = trim($_POST['no_bpjs'] ?? '');
        $alamat    = trim($_POST['alamat'] ?? '');

        try {
            $stmt = $db->prepare("
                UPDATE pasiens 
                SET nik = :nik, nama = :nama, tgl_lahir = :tgl_lahir, jk = :jk, no_telp = :no_telp, no_bpjs = :no_bpjs, alamat = :alamat
                WHERE no_rm = :no_rm
            ");
            $stmt->execute([
                'nik'       => $nik,
                'nama'      => $nama,
                'tgl_lahir' => $tgl_lahir,
                'jk'        => $jk,
                'no_telp'   => $no_telp,
                'no_bpjs'   => $no_bpjs ?: null,
                'alamat'    => $alamat,
                'no_rm'     => $no_rm
            ]);
            $this->redirect('/admin/master?tab=pasien&success=' . urlencode('Data Pasien berhasil diperbarui.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=pasien&error=' . urlencode($e->getMessage()));
        }
    }

    public function deletePasien(): void
    {
        AuthMiddleware::requireAdmin();
        $db = Container::get(PDO::class);

        $no_rm = $_POST['no_rm'] ?? '';

        try {
            $stmt = $db->prepare("DELETE FROM pasiens WHERE no_rm = :no_rm");
            $stmt->execute(['no_rm' => $no_rm]);
            $this->redirect('/admin/master?tab=pasien&success=' . urlencode('Data Pasien berhasil dihapus.'));
        } catch (Exception $e) {
            $this->redirect('/admin/master?tab=pasien&error=' . urlencode($e->getMessage()));
        }
    }
}

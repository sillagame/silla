<?php

namespace App\Presentation\Controllers;

use Container;
use PDO;
use Exception;

class DashboardController extends Controller
{
    /**
     * Halaman Beranda Publik Puskesmas Salem
     */
    public function index(): void
    {
        $db = Container::get(PDO::class);

        // 1. Dapatkan hari ini dalam Bahasa Indonesia
        $dayNames = [
            'Sunday'    => 'Minggu',
            'Monday'    => 'Senin',
            'Tuesday'   => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday'  => 'Kamis',
            'Friday'    => 'Jumat',
            'Saturday'  => 'Sabtu'
        ];
        $currentDayEng = date('l');
        $hariIni = $dayNames[$currentDayEng] ?? 'Senin';

        // 2. Ambil jadwal dokter hari ini beserta sisa kuota
        $jadwalHariIni = [];
        $dokterAktifCount = 0;
        try {
            $stmt = $db->prepare("
                SELECT jp.*, d.nama_dokter, p.nama_poli, p.kd_poli
                FROM jadwal_praktiks jp
                JOIN dokters d ON jp.kd_dokter = d.kd_dokter
                JOIN polis p ON d.kd_poli = p.kd_poli
                WHERE jp.hari = :hari
                ORDER BY jp.jam_mulai ASC
            ");
            $stmt->execute(['hari' => $hariIni]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $dokterAktifCount = count($rows);

            foreach ($rows as $row) {
                // Hitung sisa kuota berdasarkan antrian yang terdaftar hari ini
                $stmtCount = $db->prepare("
                    SELECT COUNT(*) 
                    FROM queues 
                    WHERE kd_dokter = :kd_dokter AND tanggal = CURRENT_DATE
                ");
                $stmtCount->execute(['kd_dokter' => $row['kd_dokter']]);
                $terdaftar = (int) $stmtCount->fetchColumn();

                $sisaKuota = max(0, (int) $row['kuota'] - $terdaftar);

                $jadwalHariIni[] = [
                    'kd_dokter'   => $row['kd_dokter'],
                    'nama_dokter' => $row['nama_dokter'],
                    'nama_poli'   => $row['nama_poli'],
                    'kd_poli'     => $row['kd_poli'],
                    'jam'         => substr($row['jam_mulai'], 0, 5) . ' - ' . substr($row['jam_selesai'], 0, 5),
                    'kuota'       => $row['kuota'],
                    'sisa'        => $sisaKuota
                ];
            }
        } catch (Exception $e) {
            // Silently fallback if table doesn't exist yet
        }

        // 3. Hitung antrian hari ini & antrian menunggu
        $antrianHariIniCount = 0;
        $menungguCount = 0;
        try {
            $stmtAntrian = $db->query("
                SELECT COUNT(*) FROM queues WHERE tanggal = CURRENT_DATE
            ");
            $antrianHariIniCount = (int) $stmtAntrian->fetchColumn();

            $stmtWaiting = $db->query("
                SELECT COUNT(*) FROM queues 
                WHERE tanggal = CURRENT_DATE AND status IN ('Menunggu', 'waiting')
            ");
            $menungguCount = (int) $stmtWaiting->fetchColumn();
        } catch (Exception $e) {
            // Silently fallback
        }

        $success = $_GET['success'] ?? null;
        $error   = $_GET['error']   ?? null;

        $this->render('dashboard/index', [
            'title'            => 'Puskesmas Salem - Antrian & Jadwal Online',
            'hariIni'          => $hariIni,
            'jadwalHariIni'    => $jadwalHariIni,
            'stats'            => [
                'total'    => $antrianHariIniCount,
                'waiting'  => $menungguCount,
                'dokters'  => $dokterAktifCount
            ],
            'success'          => $success ? htmlspecialchars(urldecode($success)) : null,
            'error'            => $error ? htmlspecialchars(urldecode($error)) : null,
        ]);
    }
}

<?php

namespace App\Presentation\Controllers;

use App\Presentation\Middleware\AuthMiddleware;

/**
 * AuthController — Autentikasi Admin (Single Account, Hardcode)
 *
 * Login divalidasi langsung terhadap kredensial di config/app.php.
 * Tidak ada register publik — akun admin bersifat tunggal & tetap.
 */
class AuthController extends Controller
{
    /**
     * Tampilan halaman Login
     */
    public function showLogin(): void
    {
        if (AuthMiddleware::isAuthenticated()) {
            $this->redirect('/dashboard');
        }

        $error   = isset($_GET['error'])   ? htmlspecialchars_decode(urldecode($_GET['error']))   : null;
        $success = isset($_GET['success']) ? htmlspecialchars_decode(urldecode($_GET['success'])) : null;

        $this->render('auth/login', [
            'title'   => 'Login Admin - SiLLA',
            'error'   => $error,
            'success' => $success,
        ]);
    }

    /**
     * Proses Login — validasi hardcode dari config/app.php
     */
    public function login(): void
    {
        $email    = strtolower(trim($_POST['email']    ?? ''));
        $password = $_POST['password'] ?? '';

        // Ambil kredensial dari konfigurasi
        $appConfig     = require __DIR__ . '/../../../config/app.php';
        $adminEmail    = strtolower($appConfig['admin']['email']    ?? 'admin@silla.com');
        $adminPassword = $appConfig['admin']['password']             ?? 'admin123';
        $adminName     = $appConfig['admin']['name']                 ?? 'Administrator';

        if ($email !== $adminEmail || $password !== $adminPassword) {
            $this->redirect('/login?error=' . urlencode('Email atau Password admin salah.'));
            return;
        }

        // Set stateless signed cookie (berfungsi di Vercel serverless)
        AuthMiddleware::setAuthCookie([
            'uid'   => 'u_admin',
            'email' => $adminEmail,
            'name'  => $adminName,
            'role'  => 'admin',
        ]);

        $this->redirect('/dashboard');
    }

    /**
     * Proses Logout
     */
    public function logout(): void
    {
        AuthMiddleware::clearAuthCookie();
        $this->redirect('/login?success=' . urlencode('Anda berhasil keluar dari sistem.'));
    }
}

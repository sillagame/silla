<?php

namespace App\Presentation\Controllers;

use Container;
use App\Application\UseCases\Auth\LoginUser;
use App\Application\UseCases\Auth\RegisterUser;
use App\Application\UseCases\Auth\LogoutUser;
use App\Presentation\Middleware\AuthMiddleware;
use Exception;

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

        // Baca pesan dari query param (kompatibel dengan serverless Vercel)
        // Fallback ke session untuk kompatibilitas lokal
        $error   = isset($_GET['error'])   ? htmlspecialchars_decode(urldecode($_GET['error']))   : ($_SESSION['auth_error']   ?? null);
        $success = isset($_GET['success']) ? htmlspecialchars_decode(urldecode($_GET['success'])) : ($_SESSION['auth_success'] ?? null);
        unset($_SESSION['auth_error'], $_SESSION['auth_success']);

        $this->render('auth/login', [
            'title'   => 'Login - SiLLA',
            'error'   => $error,
            'success' => $success,
        ]);
    }

    /**
     * Proses Login
     */
    public function login(): void
    {
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';

        try {
            /** @var LoginUser $loginUseCase */
            $loginUseCase = Container::get(LoginUser::class);
            $result = $loginUseCase->execute($email, $password);

            // Simpan detail user ke session
            $_SESSION['user_uid']   = $result['user']->uid;
            $_SESSION['user_email'] = $result['user']->email;
            $_SESSION['user_name']  = $result['user']->displayName;
            $_SESSION['user_role']  = $result['user']->role;

            $this->redirect('/dashboard');

        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'SQLSTATE') || str_contains($msg, 'Connection') || str_contains($msg, 'could not')) {
                $msg = 'Gagal terhubung ke server database. Periksa konfigurasi koneksi atau coba beberapa saat lagi.';
            }
            $this->redirect('/login?error=' . urlencode($msg));
        }
    }

    /**
     * Tampilan halaman Register
     */
    public function showRegister(): void
    {
        if (AuthMiddleware::isAuthenticated()) {
            $this->redirect('/dashboard');
        }

        $error = isset($_GET['error']) ? htmlspecialchars_decode(urldecode($_GET['error'])) : ($_SESSION['auth_error'] ?? null);
        unset($_SESSION['auth_error']);

        $this->render('auth/register', [
            'title' => 'Pendaftaran Akun - SiLLA',
            'error' => $error,
        ]);
    }

    /**
     * Proses Pendaftaran/Register
     */
    public function register(): void
    {
        $name     = $_POST['name']     ?? '';
        $email    = $_POST['email']    ?? '';
        $password = $_POST['password'] ?? '';
        $role     = 'officer';

        try {
            /** @var RegisterUser $registerUseCase */
            $registerUseCase = Container::get(RegisterUser::class);
            $registerUseCase->execute($name, $email, $password, $role);

            $this->redirect('/login?success=' . urlencode('Pendaftaran berhasil! Silakan masuk menggunakan akun baru Anda.'));

        } catch (Exception $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'SQLSTATE') || str_contains($msg, 'Connection') || str_contains($msg, 'could not')) {
                $msg = 'Gagal terhubung ke server database. Periksa konfigurasi koneksi atau coba beberapa saat lagi.';
            }
            $this->redirect('/register?error=' . urlencode($msg));
        }
    }

    /**
     * Proses Logout
     */
    public function logout(): void
    {
        /** @var LogoutUser $logoutUseCase */
        $logoutUseCase = Container::get(LogoutUser::class);
        $logoutUseCase->execute();

        $this->redirect('/login?success=' . urlencode('Anda berhasil keluar dari sistem.'));
    }
}

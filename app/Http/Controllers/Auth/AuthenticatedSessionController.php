<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\User;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        // Lakukan autentikasi seperti biasa
        $request->authenticate();

        // **JANGAN LOGIN-KAN DULU**
        // Ambil data user yang berhasil diautentikasi
        $user = Auth::getProvider()->retrieveByCredentials($request->only('npk', 'password'));

        // 1. Generate dan simpan OTP ke database
        // Anda perlu menambahkan kolom `otp_code` (string, nullable) dan
        // `otp_expires_at` (timestamp, nullable) di tabel 'users'
        $user->otp_code = rand(100000, 999999); // Generate 6 digit OTP
        $user->otp_expires_at = now()->addMinutes(5); // OTP berlaku 5 menit
        $user->save();

        // TODO: Kirim OTP ke nomor telepon user via WhatsApp/SMS
        // Contoh: send_otp_to_whatsapp($user->phone_number, $user->otp_code);

        // 2. Simpan ID user ke dalam session untuk verifikasi nanti
        $request->session()->put('user_id_for_otp_verification', $user->id);
        
        // 3. Alihkan ke halaman verifikasi OTP
        return redirect()->route('otp.show')
            ->with('info', 'Kode OTP telah dikirim ke nomor telepon Anda.')
            ->with('otp_for_testing', $user->otp_code);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

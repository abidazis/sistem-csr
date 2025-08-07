<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

class OtpVerificationController extends Controller
{
    /**
     * Menampilkan halaman form verifikasi OTP.
     */
    public function show()
    {
        // Pastikan user datang dari halaman login yang benar
        if (! session('user_id_for_otp_verification')) {
            return redirect()->route('login');
        }

        // Halaman view harus berada di 'auth.otp-verification'
        return view('auth.otp-verification');
    }

    /**
     * Memverifikasi OTP yang dimasukkan.
     */
    public function verify(Request $request)
    {
        // Pastikan validasi hanya 'required' dan 'numeric'
        $request->validate([
            'otp_code' => ['required', 'string', 'digits:6'], // Direkomendasikan 'string' dan 'digits:6'
        ]);

        $userId = session('user_id_for_otp_verification');

        if (!$userId) {
            return redirect()->route('login')->withErrors(['otp_code' => 'Sesi verifikasi telah berakhir. Silakan login kembali.']);
        }

        $user = User::find($userId);

        // Validasi OTP: cek kode dan masa berlakunya
        if (!$user || $user->otp_code !== $request->otp_code || now()->gt($user->otp_expires_at)) {
            return back()->withErrors(['otp_code' => 'Kode OTP tidak valid atau telah kedaluwarsa.']);
        }

        // Jika OTP valid, bersihkan data OTP di database
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Login-kan user secara permanen
        Auth::login($user);
        session()->forget('user_id_for_otp_verification');
        
        // Regenerate session setelah login sukses
        $request->session()->regenerate();

        // Redirect ke Halaman Dashboard
        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
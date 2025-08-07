<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Mews\Captcha\Facades\Captcha;
use App\Http\Controllers\OtpVerificationController;
use App\Http\Middleware\OtpRequiredMiddleware;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('captcha/reload', function() {
    return response()->json(['captcha' => captcha_img('flat')]);
})->name('captcha.reload');

// === PERBAIKAN: JANGAN GUNAKAN MIDDLEWARE DI SINI ===
// Route::middleware('otp.required')->group(function () {
//     Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show');
//     Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify');
// });

// === SOLUSI: APLIKASIKAN MIDDLEWARE LANGSUNG PADA ROUTE ===
Route::get('/verify-otp', [OtpVerificationController::class, 'show'])->name('otp.show')->middleware(OtpRequiredMiddleware::class);
Route::post('/verify-otp', [OtpVerificationController::class, 'verify'])->name('otp.verify')->middleware(OtpRequiredMiddleware::class);

require __DIR__.'/auth.php';
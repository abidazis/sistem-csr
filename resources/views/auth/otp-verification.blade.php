<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Verifikasi OTP</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-100">
        
        <div class="text-center mb-8">
            <div>
                <img src="{{ asset('images/kayaba-logo.png') }}" alt="KYB Logo" class="w-40 mx-auto">
                <p class="mt-2 text-sm text-gray-600">Our Precision, Your Advantage</p>
            </div>
        </div>

        <div class="w-full sm:max-w-md px-10 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-center text-xl font-bold text-kyb-red uppercase mb-8">
                Verifikasi Kode OTP
            </h2>

            @if(session('otp_for_testing'))
                <div class="mb-4 p-3 bg-yellow-100 border border-yellow-300 text-yellow-800 text-center rounded-md">
                    <strong>Untuk Testing:</strong> Kode OTP Anda adalah <strong>{{ session('otp_for_testing') }}</strong>
                </div>
            @endif
            
            <x-auth-session-status class="mb-4" :status="session('info')" />

            <form method="POST" action="{{ route('otp.verify') }}">
                @csrf
                <p class="text-sm text-gray-600 text-center mb-6">
                    Kode OTP telah dikirim ke nomor telepon Anda. Silakan masukkan kode di bawah ini.
                </p>

                <div class="mt-4">
                    <x-input-label for="otp_code" :value="__('Kode OTP')" class="sr-only" />
                    <div id="otp-input-container" class="flex justify-center gap-2">
                        {{-- Inputan kotak-kotak akan di-render di sini --}}
                    </div>
                    <x-input-error :messages="$errors->get('otp_code')" class="mt-2 text-center" />
                    <input type="hidden" name="otp_code" id="hidden-otp-code">
                </div>

                <div class="flex items-center justify-center mt-8">
                    <button type="submit" class="w-full bg-kyb-red text-white font-bold py-3 px-4 rounded-md uppercase hover:bg-red-700 transition duration-300">
                        Verifikasi
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-12 text-center text-sm text-gray-500">
            Copyright © 2025 PT Kayaba Indonesia
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('otp-input-container');
            const hiddenInput = document.getElementById('hidden-otp-code');
            const numInputs = 6; // Jumlah digit OTP, sesuaikan jika perlu

            for (let i = 0; i < numInputs; i++) {
                const input = document.createElement('input');
                input.type = 'text';
                input.maxLength = 1;
                input.className = 'w-10 h-10 text-center text-xl font-bold border-2 border-gray-300 rounded-md focus:border-kyb-red focus:ring-0';
                
                // Tambahkan event listener untuk handling input
                input.addEventListener('input', (e) => {
                    // Pindah fokus ke input berikutnya
                    if (e.target.value && i < numInputs - 1) {
                        container.children[i + 1].focus();
                    }
                    updateHiddenInput();
                });

                input.addEventListener('keydown', (e) => {
                    // Pindah fokus ke input sebelumnya saat backspace
                    if (e.key === 'Backspace' && e.target.value === '' && i > 0) {
                        container.children[i - 1].focus();
                    }
                });

                container.appendChild(input);
            }

            function updateHiddenInput() {
                let otpCode = '';
                for (let i = 0; i < numInputs; i++) {
                    otpCode += container.children[i].value;
                }
                hiddenInput.value = otpCode;
            }

            // Fokus otomatis ke input pertama saat halaman dimuat
            container.children[0].focus();
        });
    </script>
</body>
</html>
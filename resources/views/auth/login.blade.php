<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-100">
        
        <div class="text-center mb-8">
            <div>
                <img src="{{ asset('images/kayaba-logo.png') }}" alt="KYB Logo" class="w-60 mx-auto">
            </div>
        </div>

        <div class="w-full sm:max-w-md px-10 py-8 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <h2 class="text-center text-xl font-bold text-kyb-red uppercase mb-8">
                Sign In
            </h2>

            @if ($errors->any())
                <div class="mb-4">
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-input-label for="username" value="Username" />
                    <x-text-input id="username" class="block mt-1 w-full bg-transparent border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-kyb-red" type="text" name="npk" :value="old('npk')" required autofocus />
                </div>

                <div class="mt-6">
                    <x-input-label for="password" value="Password" />
                    <x-text-input id="password" class="block mt-1 w-full bg-transparent border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-kyb-red"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />
                </div>

                <div class="mt-6">
                    <x-input-label for="captcha" value="Captcha" />

                    <div class="flex items-center gap-4 mt-1">
                        <span id="captcha-img" class="flex-grow">{!! captcha_img('flat') !!}</span>
                        <button type="button" id="reload-captcha" class="p-1 text-gray-500 hover:text-gray-800 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.664 0l3.181-3.183m-4.991-2.696a8.25 8.25 0 00-11.664 0l-3.181 3.183" />
                        </svg>
                        </button>
                    </div>

                    <x-text-input id="captcha" class="block mt-2 w-full bg-transparent border-0 border-b-2 border-gray-300 focus:ring-0 focus:border-kyb-red" type="text" name="captcha" required />
                </div>
                <div class="mt-8">
                    <button type="submit" class="w-full bg-kyb-red text-white font-bold py-3 px-4 rounded-md uppercase hover:bg-red-700 transition duration-300">
                        Submit
                    </button>
                </div>
            </form>
        </div>
        
        <div class="mt-12 text-center text-sm text-gray-500">
            Copyright © 2025 PT Kayaba Indonesia
        </div>
    </div>

    <script>
        document.getElementById('reload-captcha').addEventListener('click', function () {
            fetch('{{ route("captcha.reload") }}')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('captcha-img').innerHTML = data.captcha;
                });
        });
    </script>
</body>
</html>
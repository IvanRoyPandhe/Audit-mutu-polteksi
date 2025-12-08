<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - IPASS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-100 to-gray-200 min-h-screen flex flex-col items-center justify-center p-4">
    <div class="flex flex-col lg:flex-row max-w-md lg:max-w-5xl my-auto w-full bg-white rounded-3xl shadow-2xl overflow-hidden">
        <div class="hidden lg:flex w-full lg:w-1/2 bg-gradient-to-br from-blue-600 to-purple-700 p-8 lg:p-12 text-white flex-col justify-center">
            <div class="mb-8 flex justify-center">
                <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl font-bold text-center mb-2">IPASS</h1>
            <p class="text-center text-blue-100 mb-8">Sistem Informasi Akademik</p>
            <h2 class="text-2xl font-semibold text-center mb-6">Selamat Datang</h2>
            <p class="text-center text-sm mb-8 leading-relaxed">Akses portal akademik Anda untuk mengelola data mahasiswa, jadwal kuliah, nilai, dan informasi akademik lainnya dengan mudah dan aman.</p>
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm">Manajemen Data Mahasiswa</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm">Jadwal Kuliah Real-time</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm">Monitoring Nilai & KHS</span>
                </div>
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span class="text-sm">Notifikasi Akademik</span>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-1/2 p-8 lg:p-12 flex flex-col justify-center">
            <div class="text-center lg:hidden mb-8">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-600 to-purple-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">IPASS</h1>
                <p class="text-sm text-gray-600">Sistem Informasi Akademik</p>
            </div>
            <h2 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-2 text-center lg:text-left">Masuk ke Akun</h2>
            <p class="text-gray-600 text-sm mb-8 text-center lg:text-left">Silakan masukkan kredensial Anda untuk mengakses sistem</p>

            @if(session('status'))
                <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-lg text-sm">{{ session('status') }}</div>
            @endif

            <form method="POST" action="/login">
                @csrf
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Username / NIM</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan username atau NIM" required>
                    </div>
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-medium mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-3 top-3 text-gray-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
                        </span>
                        <input type="password" name="password" class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Masukkan password" required>
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
                    </label>
                    <a href="/forgot-password" class="text-sm text-blue-600 hover:text-blue-700">Lupa password?</a>
                </div>

                <button type="submit" class="w-full bg-blue-600 text-white py-3 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Masuk ke Sistem
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Butuh bantuan? <a href="#" class="text-blue-600 hover:text-blue-700">Hubungi IT Support</a>
            </p>
        </div>
    </div>

    <footer class="mt-auto pt-4 text-center w-full text-sm text-gray-600">
        © 2024 IPASS - Sistem Informasi Akademik. All rights reserved.
    </footer>
</body>
</html>

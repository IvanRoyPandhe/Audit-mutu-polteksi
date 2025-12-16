<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>401 - Tidak Terautentikasi</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="text-6xl font-bold text-purple-500 mb-4">401</div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Tidak Terautentikasi</h1>
            <p class="text-gray-600">Anda perlu login terlebih dahulu untuk mengakses halaman ini.</p>
        </div>
        
        <div class="bg-purple-50 border-l-4 border-purple-500 p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-purple-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-purple-700 text-sm">Silakan login dengan akun Anda</p>
            </div>
        </div>
        
        <div class="space-y-3">
            <a href="/login" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                Login Sekarang
            </a>
            <a href="/" class="block w-full bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
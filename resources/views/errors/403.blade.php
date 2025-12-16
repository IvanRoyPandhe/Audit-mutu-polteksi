<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="text-6xl font-bold text-orange-500 mb-4">403</div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Akses Ditolak</h1>
            <p class="text-gray-600">Maaf, Anda tidak memiliki izin untuk mengakses halaman ini. Hanya pengguna dengan role tertentu yang dapat mengakses.</p>
        </div>
        
        <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-orange-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-orange-700 text-sm">Hubungi administrator untuk mendapatkan akses</p>
            </div>
        </div>
        
        <div class="space-y-3">
            <a href="/dashboard" class="block w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition duration-200">
                Kembali ke Dashboard
            </a>
            <button onclick="history.back()" class="block w-full bg-gray-300 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-400 transition duration-200">
                Kembali ke Halaman Sebelumnya
            </button>
        </div>
    </div>
</body>
</html>
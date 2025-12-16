<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="mb-6">
            <div class="text-6xl font-bold text-red-500 mb-4">404</div>
            <h1 class="text-2xl font-semibold text-gray-800 mb-2">Halaman Tidak Ditemukan</h1>
            <p class="text-gray-600">Maaf, halaman yang Anda cari tidak dapat ditemukan atau telah dipindahkan.</p>
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
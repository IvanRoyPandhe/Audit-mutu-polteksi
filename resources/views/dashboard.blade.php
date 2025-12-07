<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - IPASS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <nav class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-blue-600">IPASS Dashboard</h1>
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</button>
                </form>
            </div>
        </nav>
        <div class="max-w-7xl mx-auto px-4 py-8">
            <h2 class="text-3xl font-bold mb-4">Selamat Datang, {{ auth()->user()->name }}!</h2>
            <p class="text-gray-600">Anda berhasil login ke sistem IPASS.</p>
        </div>
    </div>
</body>
</html>

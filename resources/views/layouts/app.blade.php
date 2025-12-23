<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - IPASS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Mobile Menu Button -->
        <button id="menuToggle" class="fixed top-4 left-4 z-50 lg:hidden bg-blue-600 text-white p-2 rounded-lg shadow-lg">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Sidebar -->
        <aside id="sidebar" class="fixed lg:static inset-y-0 left-0 z-40 w-64 bg-gradient-to-br from-blue-600 to-red-700 text-white transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out overflow-y-auto relative">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-600 via-purple-600 to-red-700 animate-pulse opacity-40"></div>
            <div class="absolute top-0 left-0 w-40 h-40 bg-white/30 rounded-full -ml-20 -mt-20 animate-bounce" style="animation-duration: 2s;"></div>
            <div class="absolute bottom-0 right-0 w-32 h-32 bg-white/25 rounded-full -mr-16 -mb-16 animate-ping" style="animation-duration: 3s;"></div>
            <div class="absolute top-1/2 right-0 w-20 h-20 bg-white/20 rounded-full -mr-10 animate-pulse" style="animation-duration: 2.5s;"></div>
            <div class="p-6 flex items-center relative z-10">
                <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center mr-3 p-2">
                    <img src="{{ secure_asset('storage/polteksi.png') }}" alt="Logo" class="w-full h-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                    <div style="display:none; font-size:12px; font-weight:bold; color:white;">LOGO</div>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">IPASS</h1>
                    <p class="text-sm text-blue-100">Sistem Audit Mutu</p>
                </div>
            </div>
            <nav class="mt-6 relative z-10">
                @php
                    $user = auth()->user();
                    $role = DB::table('role')->where('role_id', $user->role_id)->first();
                    $permissions = json_decode($role->permissions ?? '[]', true) ?: [];
                    $isAdmin = $user->role_id == 1;
                @endphp

                @if($isAdmin || in_array('dashboard', $permissions))
                <a href="/dashboard" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    Dashboard
                </a>
                @endif

                @if($isAdmin || in_array('standar-mutu', $permissions) || in_array('kriteria', $permissions) || in_array('indikator-kinerja', $permissions))
                <div class="px-6 py-2 text-xs font-semibold text-blue-200 uppercase mt-4">Master Data</div>
                @endif

                @if($isAdmin || in_array('standar-mutu', $permissions))
                <a href="/dashboard/standar-mutu" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Standar Mutu
                </a>
                @endif

                @if($isAdmin || in_array('kriteria', $permissions))
                <a href="/dashboard/kriteria" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/></svg>
                    Kriteria
                </a>
                @endif

                @if($isAdmin || in_array('indikator-kinerja', $permissions))
                <a href="/dashboard/indikator-kinerja" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                    Indikator Kinerja
                </a>
                @endif

                @if($isAdmin || in_array('approval', $permissions))
                <a href="/dashboard/approval" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Approval
                </a>
                @endif

                @if($isAdmin || in_array('penetapan', $permissions) || in_array('pelaksanaan', $permissions) || in_array('evaluasi', $permissions))
                <div class="px-6 py-2 text-xs font-semibold text-blue-200 uppercase mt-4">Proses</div>
                @endif

                @if($isAdmin || in_array('penetapan', $permissions))
                <a href="/dashboard/penetapan" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                    Penetapan
                </a>
                @endif

                @if($isAdmin || in_array('pelaksanaan', $permissions))
                <a href="/dashboard/pelaksanaan" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                    Pelaksanaan
                </a>
                @endif

                @if($isAdmin || in_array('evaluasi', $permissions))
                <a href="/dashboard/evaluasi" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    Evaluasi
                </a>
                @endif

                @if($isAdmin || in_array('laporan', $permissions))
                <a href="/dashboard/laporan" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/><path d="M3 8a2 2 0 012-2v10h8a2 2 0 01-2 2H5a2 2 0 01-2-2V8z"/></svg>
                    Laporan
                </a>
                @endif

                @if($isAdmin || in_array('buku-kebijakan', $permissions))
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center justify-between w-full px-6 py-3 hover:bg-white/10 text-left">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg>
                            Buku Kebijakan
                        </div>
                        <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                    </button>
                    <div x-show="open" x-transition class="bg-white/10 backdrop-blur-sm border-l-2 border-white/20">
                        <a href="/dashboard/kebijakan" class="block px-12 py-2 hover:bg-white/10 text-sm transition-colors">Kebijakan</a>
                        <a href="/dashboard/manual" class="block px-12 py-2 hover:bg-white/10 text-sm transition-colors">Manual</a>
                        <a href="/dashboard/formulir" class="block px-12 py-2 hover:bg-white/10 text-sm transition-colors">Formulir</a>
                    </div>
                </div>
                @endif

                @if($isAdmin || in_array('users', $permissions) || in_array('roles', $permissions) || in_array('units', $permissions) || in_array('unit-auditors', $permissions))
                <div class="px-6 py-2 text-xs font-semibold text-blue-200 uppercase mt-4">Management</div>
                @endif

                @if($isAdmin || in_array('users', $permissions))
                <a href="/dashboard/users" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                    Users
                </a>
                @endif

                @if($isAdmin || in_array('roles', $permissions))
                <a href="/dashboard/roles" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011 1v12a1 1 0 11-2 0V4a1 1 0 011-1zm7.707 3.293a1 1 0 010 1.414L9.414 9H17a1 1 0 110 2H9.414l1.293 1.293a1 1 0 01-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Roles
                </a>
                @endif

                @if($isAdmin || in_array('units', $permissions))
                <a href="/dashboard/units" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                    Unit Kerja
                </a>
                @endif

                @if($isAdmin || in_array('unit-auditors', $permissions))
                <a href="/dashboard/unit-auditors" class="flex items-center px-6 py-3 hover:bg-white/10">
                    <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                    Unit Auditors
                </a>
                @endif
            </nav>
            <div class="absolute bottom-0 w-64 p-6 relative z-10">
                <a href="/dashboard/profile" class="flex items-center mb-4 hover:bg-white/10 p-2 rounded-lg transition">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-blue-100">{{ auth()->user()->email }}</p>
                    </div>
                </a>
                <form method="POST" action="/dashboard/logout">
                    @csrf
                    <button class="w-full bg-white/10 hover:bg-white/20 py-2 rounded-lg text-sm">Logout</button>
                </form>
            </div>
        </aside>

        <!-- Overlay -->
        <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"></div>

        <main class="flex-1 overflow-y-auto w-full">
            <header class="bg-white shadow-sm">
                <div class="px-4 sm:px-8 py-4">
                    <h2 class="text-2xl font-bold text-gray-800">@yield('title')</h2>
                </div>
            </header>
            <div class="p-4 sm:p-8">
                @if(session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">{{ session('success') }}</div>
                @endif
                @yield('content')
            </div>
            <footer class="bg-white border-t border-gray-200 py-4 px-4 sm:px-8 mt-auto">
                <p class="text-center text-sm text-gray-600">Created by Teknologi Informasi © 2023</p>
            </footer>
        </main>
    </div>

    <script>
        const menuToggle = document.getElementById('menuToggle');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
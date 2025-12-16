@extends('layouts.app')

@section('title', 'Dashboard')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    .stat-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15), 0 10px 10px -5px rgba(0, 0, 0, 0.1);
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endpush

@section('content')
<div class="mb-8 animate-fade-in-up">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-4xl font-extrabold text-gray-900 mb-2">
                Selamat Datang, <span class="bg-gradient-to-r from-blue-600 to-red-700 bg-clip-text text-transparent">{{ auth()->user()->name }}</span>! 👋
            </h1>
            <p class="text-gray-600 text-lg">Sistem Informasi Audit Penjaminan Mutu Akademik</p>
        </div>
        <div class="hidden md:block">
            <div class="bg-gradient-to-r from-blue-600 to-red-700 text-white px-6 py-3 rounded-xl shadow-lg">
                <div class="text-sm font-medium">{{ now()->isoFormat('dddd, D MMMM Y') }}</div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->role_id != 3)
<div class="mb-8 bg-white rounded-2xl shadow-xl p-6 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.1s;">
    <div class="flex items-center mb-4">
        <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-red-700 rounded-xl flex items-center justify-center mr-3">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Filter Data</h3>
    </div>
    <form method="GET" class="flex flex-col sm:flex-row flex-wrap gap-3 items-stretch sm:items-end">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Unit Kerja</label>
            <select name="unit_id" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <option value="">Semua Unit</option>
                @foreach($units as $unit)
                <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
            <select name="tahun" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                <option value="">Semua Tahun</option>
                @foreach($tahun_list as $t)
                <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-gradient-to-r from-blue-600 to-red-700 text-white px-8 py-2.5 rounded-xl hover:from-blue-700 hover:to-red-800 transition shadow-lg hover:shadow-xl font-medium">
            Terapkan Filter
        </button>
        @if(request('unit_id') || request('tahun'))
        <a href="/dashboard" class="bg-gray-100 text-gray-700 px-8 py-2.5 rounded-xl hover:bg-gray-200 transition font-medium">
            Reset
        </a>
        @endif
    </form>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Penetapan -->
    <div class="stat-card bg-gradient-to-br from-blue-500 via-blue-600 to-blue-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative animate-fade-in-up" style="animation-delay: 0.2s;">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <p class="text-blue-100 text-sm font-semibold mb-2 uppercase tracking-wide">Total Penetapan</p>
            <p class="text-5xl font-extrabold mb-1">{{ $stats['total_penetapan'] }}</p>
            <p class="text-blue-200 text-xs">Dokumen penetapan terdaftar</p>
        </div>
    </div>

    <!-- Total Pelaksanaan -->
    <div class="stat-card bg-gradient-to-br from-emerald-500 via-green-600 to-teal-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative animate-fade-in-up" style="animation-delay: 0.3s;">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <p class="text-green-100 text-sm font-semibold mb-2 uppercase tracking-wide">Total Pelaksanaan</p>
            <p class="text-5xl font-extrabold mb-1">{{ $stats['total_pelaksanaan'] }}</p>
            <p class="text-green-200 text-xs">Kegiatan dalam proses</p>
        </div>
    </div>

    <!-- Pelaksanaan Selesai -->
    <div class="stat-card bg-gradient-to-br from-purple-500 via-purple-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative animate-fade-in-up" style="animation-delay: 0.4s;">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <p class="text-purple-100 text-sm font-semibold mb-2 uppercase tracking-wide">Pelaksanaan Selesai</p>
            <p class="text-5xl font-extrabold mb-1">{{ $stats['pelaksanaan_selesai'] }}</p>
            <p class="text-purple-200 text-xs">Kegiatan telah selesai</p>
        </div>
    </div>

    <!-- Total Audit -->
    <div class="stat-card bg-gradient-to-br from-orange-500 via-red-500 to-pink-600 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative animate-fade-in-up" style="animation-delay: 0.5s;">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <p class="text-orange-100 text-sm font-semibold mb-2 uppercase tracking-wide">Total Audit</p>
            <p class="text-5xl font-extrabold mb-1">{{ $stats['total_audit'] }}</p>
            <p class="text-orange-200 text-xs">Audit telah dilakukan</p>
        </div>
    </div>
</div>

<!-- Audit Results Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Temuan Mayor -->
    <div class="stat-card bg-gradient-to-br from-red-500 via-red-600 to-red-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative animate-fade-in-up" style="animation-delay: 0.6s;">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <p class="text-red-100 text-sm font-semibold mb-2 uppercase tracking-wide">Temuan Mayor</p>
            <p class="text-5xl font-extrabold mb-1">{{ $stats['audit_mayor'] }}</p>
            <p class="text-red-200 text-xs">Memerlukan tindakan segera</p>
        </div>
    </div>

    <!-- Temuan Minor -->
    <div class="stat-card bg-gradient-to-br from-yellow-500 via-yellow-600 to-orange-600 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative animate-fade-in-up" style="animation-delay: 0.7s;">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <p class="text-yellow-100 text-sm font-semibold mb-2 uppercase tracking-wide">Temuan Minor</p>
            <p class="text-5xl font-extrabold mb-1">{{ $stats['audit_minor'] }}</p>
            <p class="text-yellow-200 text-xs">Perlu perbaikan</p>
        </div>
    </div>

    <!-- Observasi -->
    <div class="stat-card bg-gradient-to-br from-blue-500 via-blue-600 to-indigo-700 rounded-2xl shadow-xl p-6 text-white overflow-hidden relative animate-fade-in-up" style="animation-delay: 0.8s;">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
        <div class="relative z-10">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/><path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/></svg>
                </div>
            </div>
            <p class="text-blue-100 text-sm font-semibold mb-2 uppercase tracking-wide">Observasi</p>
            <p class="text-5xl font-extrabold mb-1">{{ $stats['audit_observasi'] }}</p>
            <p class="text-blue-200 text-xs">Catatan pengamatan</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Pelaksanaan Per Bulan -->
    <div class="bg-white rounded-2xl lg:col-span-2 shadow-xl p-6 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.6s;">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-red-700 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Tren Pelaksanaan</h3>
                    <p class="text-sm text-gray-500">Statistik bulanan</p>
                </div>
            </div>
            <div class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-lg text-sm font-semibold">
                {{ array_sum(array_column($chartData['pelaksanaan_per_bulan']->toArray(), 'total')) }} Total
            </div>
        </div>
        <div class="chart-container">
            <canvas id="pelaksanaanChart"></canvas>
        </div>
    </div>

    <!-- Evaluasi Kesesuaian -->
    <div class="bg-white lg:col-span-1 rounded-2xl shadow-xl p-6 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.7s;">
        <div class="flex items-center mb-6">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-red-700 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Evaluasi Audit</h3>
                <p class="text-sm text-gray-500">Distribusi hasil</p>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="evaluasiChart"></canvas>
        </div>
        @if($chartData['evaluasi']->isEmpty())
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <p class="text-gray-500 text-sm">Belum ada data evaluasi</p>
        </div>
        @endif
    </div>
</div>

@if(auth()->user()->role_id != 3)
<div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 animate-fade-in-up" style="animation-delay: 0.8s;">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-red-700 rounded-xl flex items-center justify-center mr-3 shadow-lg">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Penetapan Per Unit Kerja</h3>
                <p class="text-sm text-gray-500">Perbandingan antar unit</p>
            </div>
        </div>
    </div>
    <div style="height: 400px;">
        <canvas id="unitChart"></canvas>
    </div>
    @if($chartData['per_unit']->isEmpty())
    <div class="text-center py-12">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <p class="text-gray-500">Belum ada data penetapan per unit</p>
    </div>
    @endif
</div>
@endif

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart.js default config
Chart.defaults.font.family = "'Inter', sans-serif";
Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
Chart.defaults.plugins.tooltip.padding = 12;
Chart.defaults.plugins.tooltip.cornerRadius = 8;

// Evaluasi Chart (Doughnut)
@if(!$chartData['evaluasi']->isEmpty())
const evaluasiCtx = document.getElementById('evaluasiChart').getContext('2d');
new Chart(evaluasiCtx, {
    type: 'doughnut',
    data: {
        labels: [@foreach($chartData['evaluasi'] as $item)'{{ $item->evaluasi_kesesuaian }}',@endforeach],
        datasets: [{
            data: [@foreach($chartData['evaluasi'] as $item){{ $item->total }},@endforeach],
            backgroundColor: [
                'rgba(16, 185, 129, 0.9)',  // green
                'rgba(59, 130, 246, 0.9)',  // blue
                'rgba(239, 68, 68, 0.9)',   // red
                'rgba(245, 158, 11, 0.9)',  // amber
                'rgba(139, 92, 246, 0.9)'   // purple
            ],
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: { size: 11, weight: '600' },
                    usePointStyle: true,
                    pointStyle: 'circle'
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return ` ${context.label}: ${context.parsed} (${percentage}%)`;
                    }
                }
            }
        }
    }
});
@endif

// Pelaksanaan Per Bulan Chart (Area Line)
const pelaksanaanCtx = document.getElementById('pelaksanaanChart').getContext('2d');
const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const pelaksanaanData = Array(12).fill(0);
@foreach($chartData['pelaksanaan_per_bulan'] as $item)
pelaksanaanData[{{ $item->bulan - 1 }}] = {{ $item->total }};
@endforeach

const gradient = pelaksanaanCtx.createLinearGradient(0, 0, 0, 300);
gradient.addColorStop(0, 'rgba(16, 185, 129, 0.3)');
gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

new Chart(pelaksanaanCtx, {
    type: 'line',
    data: {
        labels: bulanNames,
        datasets: [{
            label: 'Jumlah Pelaksanaan',
            data: pelaksanaanData,
            borderColor: 'rgb(16, 185, 129)',
            backgroundColor: gradient,
            tension: 0.4,
            fill: true,
            pointRadius: 6,
            pointHoverRadius: 8,
            pointBackgroundColor: 'rgb(16, 185, 129)',
            pointBorderColor: '#fff',
            pointBorderWidth: 3,
            pointHoverBorderWidth: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                },
                ticks: {
                    stepSize: 1,
                    font: { size: 11 }
                }
            },
            x: {
                grid: { display: false },
                ticks: { font: { size: 11 } }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                displayColors: false,
                callbacks: {
                    title: function(context) {
                        return 'Bulan ' + context[0].label;
                    },
                    label: function(context) {
                        return 'Total: ' + context.parsed.y + ' pelaksanaan';
                    }
                }
            }
        }
    }
});

@if(auth()->user()->role_id != 3 && !$chartData['per_unit']->isEmpty())
// Unit Chart (Horizontal Bar)
const unitCtx = document.getElementById('unitChart').getContext('2d');
new Chart(unitCtx, {
    type: 'bar',
    data: {
        labels: [@foreach($chartData['per_unit'] as $item)'{{ $item->nama_unit }}',@endforeach],
        datasets: [{
            label: 'Total Penetapan',
            data: [@foreach($chartData['per_unit'] as $item){{ $item->total }},@endforeach],
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(20, 184, 166, 0.8)'
            ],
            borderRadius: 10,
            borderSkipped: false,
            barThickness: 40
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            x: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)',
                    drawBorder: false
                },
                ticks: {
                    stepSize: 1,
                    font: { size: 11 }
                }
            },
            y: {
                grid: { display: false },
                ticks: {
                    font: { size: 11, weight: '600' }
                }
            }
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                displayColors: false,
                callbacks: {
                    label: function(context) {
                        return 'Total: ' + context.parsed.x + ' penetapan';
                    }
                }
            }
        }
    }
});
@endif
</script>
@endpush
@endsection

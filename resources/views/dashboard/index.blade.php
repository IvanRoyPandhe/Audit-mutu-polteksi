@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
    <p class="text-gray-600">Sistem Informasi Audit Penjaminan Mutu Akademik</p>
</div>

@if(auth()->user()->role_id != 3)
<div class="mb-6 bg-white rounded-lg shadow p-4">
    <form method="GET" class="flex gap-3 items-center">
        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd"/></svg>
        <select name="unit_id" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
            @endforeach
        </select>
        <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="">Semua Tahun</option>
            @foreach($tahun_list as $t)
            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-2 rounded-lg hover:from-blue-700 hover:to-blue-800 transition shadow-md">Filter</button>
        <a href="/dashboard" class="bg-gray-100 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-200 transition">Reset</a>
    </form>
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Total Penetapan</p>
                <p class="text-4xl font-bold">{{ $stats['total_penetapan'] }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z" clip-rule="evenodd"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-green-100 text-sm font-medium mb-1">Total Pelaksanaan</p>
                <p class="text-4xl font-bold">{{ $stats['total_pelaksanaan'] }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-purple-100 text-sm font-medium mb-1">Pelaksanaan Selesai</p>
                <p class="text-4xl font-bold">{{ $stats['pelaksanaan_selesai'] }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-orange-100 text-sm font-medium mb-1">Total Audit</p>
                <p class="text-4xl font-bold">{{ $stats['total_audit'] }}</p>
            </div>
            <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center backdrop-blur-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/></svg>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-xl col-span-2 shadow-lg p-6 border border-gray-100">
        <div class="flex items-center mb-4 ">
            <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-teal-600 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Pelaksanaan Per Bulan</h3>
        </div>
        <canvas id="pelaksanaanChart" class="max-h-64"></canvas>
    </div>
    <div class="bg-white col-span-1 rounded-xl shadow-lg p-6 border border-gray-100">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/></svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800">Evaluasi Kesesuaian</h3>
        </div>
        <canvas id="evaluasiChart" class="max-h-64"></canvas>
    </div>
</div>

@if(auth()->user()->role_id != 3)
<div class="bg-white rounded-xl shadow-lg p-6 border border-gray-100">
    <div class="flex items-center mb-4">
        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-600 rounded-lg flex items-center justify-center mr-3">
            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/></svg>
        </div>
        <h3 class="text-lg font-bold text-gray-800">Penetapan Per Unit</h3>
    </div>
    <canvas id="unitChart" class="max-h-80"></canvas>
</div>
@endif

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Evaluasi Chart
const evaluasiCtx = document.getElementById('evaluasiChart').getContext('2d');
new Chart(evaluasiCtx, {
    type: 'doughnut',
    data: {
        labels: [@foreach($chartData['evaluasi'] as $item)'{{ $item->evaluasi_kesesuaian }}',@endforeach],
        datasets: [{
            data: [@foreach($chartData['evaluasi'] as $item){{ $item->total }},@endforeach],
            backgroundColor: ['#10b981', '#3b82f6', '#ef4444', '#f59e0b'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { position: 'bottom', labels: { padding: 15, font: { size: 12 } } }
        }
    }
});

// Pelaksanaan Per Bulan Chart
const pelaksanaanCtx = document.getElementById('pelaksanaanChart').getContext('2d');
const bulanNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
const pelaksanaanData = Array(12).fill(0);
@foreach($chartData['pelaksanaan_per_bulan'] as $item)
pelaksanaanData[{{ $item->bulan - 1 }}] = {{ $item->total }};
@endforeach

new Chart(pelaksanaanCtx, {
    type: 'line',
    data: {
        labels: bulanNames,
        datasets: [{
            label: 'Jumlah Pelaksanaan',
            data: pelaksanaanData,
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true,
            pointRadius: 5,
            pointHoverRadius: 7,
            pointBackgroundColor: 'rgb(34, 197, 94)',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(0, 0, 0, 0.05)' } },
            x: { grid: { display: false } }
        },
        plugins: {
            legend: { display: false }
        }
    }
});

@if(auth()->user()->role_id != 3)
// Unit Chart
const unitCtx = document.getElementById('unitChart').getContext('2d');
new Chart(unitCtx, {
    type: 'bar',
    data: {
        labels: [@foreach($chartData['per_unit'] as $item)'{{ $item->nama_unit }}',@endforeach],
        datasets: [{
            label: 'Total Penetapan',
            data: [@foreach($chartData['per_unit'] as $item){{ $item->total }},@endforeach],
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderRadius: 8,
            borderSkipped: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        scales: {
            y: { beginAtZero: true, grid: { display: false } },
            x: { grid: { display: false } }
        },
        plugins: {
            legend: { display: false }
        }
    }
});
@endif
</script>
@endsection

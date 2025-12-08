@extends('layouts.app')

@section('title', 'Detail Kriteria')

@section('content')
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
        <h2 class="text-xl font-semibold text-gray-800">Detail Kriteria</h2>
        <a href="/dashboard/kriteria" class="text-blue-600 hover:text-blue-800">← Kembali</a>
    </div>

    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <label class="block text-blue-700 text-xs font-semibold uppercase mb-2">Kode Kriteria</label>
                <p class="text-gray-900 font-bold text-lg">{{ $kriteria->kode_kriteria }}</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <label class="block text-gray-600 text-xs font-semibold uppercase mb-2">Tahun Berlaku</label>
                <p class="text-gray-900 font-semibold text-lg">{{ $kriteria->tahun_berlaku }}</p>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <label class="block text-gray-600 text-xs font-semibold uppercase mb-2">Status</label>
                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full 
                    @if($kriteria->status == 'Disetujui') bg-green-100 text-green-800
                    @elseif($kriteria->status == 'Ditolak') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ $kriteria->status }}
                </span>
            </div>
        </div>

        <div class="mt-6 space-y-4">
            <div class="border-l-4 border-blue-500 pl-4">
                <label class="block text-gray-600 text-xs font-semibold uppercase mb-1">Nama Kriteria</label>
                <p class="text-gray-900 text-base">{{ $kriteria->nama_kriteria }}</p>
            </div>

            <div class="border-l-4 border-gray-300 pl-4">
                <label class="block text-gray-600 text-xs font-semibold uppercase mb-1">Standar Mutu</label>
                <p class="text-gray-900 text-base">{{ $kriteria->kode_standar }} - {{ $kriteria->nama_standar }}</p>
            </div>

            <div class="border-l-4 border-gray-300 pl-4">
                <label class="block text-gray-600 text-xs font-semibold uppercase mb-1">Deskripsi</label>
                <p class="text-gray-900 text-base">{{ $kriteria->deskripsi ?? '-' }}</p>
            </div>
        </div>

        <div class="mt-6 pt-6 border-t border-gray-200">
            <h3 class="text-sm font-semibold text-gray-700 uppercase mb-4">Informasi Tambahan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div>
                        <label class="block text-gray-600 text-xs font-medium">Dibuat Oleh</label>
                        <p class="text-gray-900">{{ $kriteria->pembuat }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <svg class="w-5 h-5 text-gray-400 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <label class="block text-gray-600 text-xs font-medium">Tanggal Dibuat</label>
                        <p class="text-gray-900">{{ date('d/m/Y H:i', strtotime($kriteria->tanggal_dibuat)) }}</p>
                    </div>
                </div>

                @if($kriteria->penyetuju)
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <label class="block text-gray-600 text-xs font-medium">Disetujui Oleh</label>
                        <p class="text-gray-900">{{ $kriteria->penyetuju }}</p>
                    </div>
                </div>

                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div>
                        <label class="block text-gray-600 text-xs font-medium">Tanggal Disetujui</label>
                        <p class="text-gray-900">{{ date('d/m/Y', strtotime($kriteria->tanggal_disetujui)) }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

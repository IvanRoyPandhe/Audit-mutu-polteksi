@extends('layouts.app')

@section('title', 'Detail Hasil Audit')

@section('content')
<div class="mb-6">
    <a href="/dashboard/evaluasi" class="text-blue-600 hover:text-blue-800 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Daftar Evaluasi
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
        <h1 class="text-2xl font-bold">Detail Hasil Audit</h1>
        <p class="text-blue-100 mt-2">{{ $audit->nama_unit }} - {{ $audit->tahun }}</p>
    </div>

    <div class="p-6">
        <!-- Status Badge -->
        <div class="mb-6 flex justify-between items-start">
            <div class="flex items-center space-x-4">
                <span class="px-4 py-2 rounded-full text-sm font-medium
                    @if($audit->evaluasi_kesesuaian == 'Sesuai') bg-green-100 text-green-800
                    @elseif($audit->evaluasi_kesesuaian == 'Melampaui') bg-blue-100 text-blue-800
                    @elseif($audit->evaluasi_kesesuaian == 'Tidak Sesuai') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ $audit->evaluasi_kesesuaian }}
                </span>
                <span class="px-3 py-1 rounded-full text-xs font-medium
                    @if($audit->hasil_audit == 'Tidak Ada') bg-green-100 text-green-800
                    @elseif($audit->hasil_audit == 'Minor') bg-yellow-100 text-yellow-800
                    @elseif($audit->hasil_audit == 'Mayor') bg-red-100 text-red-800
                    @else bg-blue-100 text-blue-800 @endif">
                    Temuan: {{ $audit->hasil_audit }}
                </span>
            </div>
            <div class="text-right text-sm text-gray-600">
                <p><strong>Tanggal Audit:</strong> {{ date('d F Y', strtotime($audit->tanggal_audit)) }}</p>
                <p><strong>Auditor:</strong> {{ $audit->auditor }}</p>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Kriteria & Indikator -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Kriteria & Indikator
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Kriteria:</label>
                            <p class="text-gray-800">{{ $audit->nama_kriteria }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Indikator Kinerja:</label>
                            <p class="text-gray-800">{{ $audit->nama_indikator }}</p>
                        </div>
                        @if($audit->keterangan)
                        <div>
                            <label class="text-sm font-medium text-gray-600">Keterangan:</label>
                            <p class="text-gray-800">{{ $audit->keterangan }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Target & Realisasi -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Target & Realisasi
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Target Capaian:</label>
                            @php
                                $targets = json_decode($audit->target_capaian, true);
                            @endphp
                            @if(is_array($targets))
                                @foreach($targets as $target)
                                    <p class="text-gray-800">{{ $target }}</p>
                                @endforeach
                            @else
                                <p class="text-gray-800">{{ $audit->target_capaian ?: '-' }}</p>
                            @endif
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Realisasi:</label>
                            <p class="text-gray-800">{{ $audit->tanggal_realisasi ? date('d/m/Y', strtotime($audit->tanggal_realisasi)) : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Status:</label>
                            <p class="text-gray-800">{{ $audit->status ?: 'Draft' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Tahun:</label>
                            <p class="text-gray-800">{{ $audit->tahun }}</p>
                        </div>
                    </div>
                </div>

                <!-- Anggaran & PIC -->
                <div class="bg-green-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Anggaran & PIC
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Anggaran:</label>
                            <p class="text-gray-800">{{ $audit->anggaran ? 'Rp ' . number_format($audit->anggaran, 0, ',', '.') : '-' }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">PIC (Person in Charge):</label>
                            <p class="text-gray-800">{{ $audit->pic ?: '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Dokumen Pendukung -->
                @if($audit->dokumen_link)
                <div class="bg-purple-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Dokumen Pendukung
                    </h3>
                    <div class="space-y-2">
                        @php
                            $dokumen = json_decode($audit->dokumen_link, true);
                        @endphp
                        @if(is_array($dokumen))
                            @foreach($dokumen as $dok)
                                <a href="{{ $dok['url'] }}" target="_blank" class="flex items-center p-2 bg-white rounded border hover:bg-gray-50">
                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                    </svg>
                                    {{ $dok['judul'] }}
                                </a>
                            @endforeach
                        @else
                            <a href="{{ $audit->dokumen_link }}" target="_blank" class="flex items-center p-2 bg-white rounded border hover:bg-gray-50">
                                <svg class="w-4 h-4 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                </svg>
                                Lihat Dokumen
                            </a>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Hasil Audit -->
                <div class="bg-orange-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Hasil Audit
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-600">Evaluasi Kesesuaian:</label>
                            <p class="text-lg font-semibold
                                @if($audit->evaluasi_kesesuaian == 'Sesuai') text-green-600
                                @elseif($audit->evaluasi_kesesuaian == 'Melampaui') text-blue-600
                                @elseif($audit->evaluasi_kesesuaian == 'Tidak Sesuai') text-red-600
                                @else text-yellow-600 @endif">
                                {{ $audit->evaluasi_kesesuaian }}
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-600">Hasil Audit (Temuan):</label>
                            <p class="text-lg font-semibold
                                @if($audit->hasil_audit == 'Tidak Ada') text-green-600
                                @elseif($audit->hasil_audit == 'Minor') text-yellow-600
                                @elseif($audit->hasil_audit == 'Mayor') text-red-600
                                @else text-blue-600 @endif">
                                {{ $audit->hasil_audit }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Rekomendasi & Catatan -->
                @if($audit->rekomendasi_perbaikan || $audit->catatan_penutupan)
                <div class="bg-yellow-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Rekomendasi & Catatan
                    </h3>
                    <div class="space-y-4">
                        @if($audit->rekomendasi_perbaikan)
                        <div class="bg-white p-3 rounded border-l-4 border-yellow-500">
                            <label class="text-sm font-medium text-gray-600">Rekomendasi Perbaikan:</label>
                            <p class="text-gray-800 mt-1">{{ $audit->rekomendasi_perbaikan }}</p>
                        </div>
                        @endif
                        @if($audit->catatan_penutupan)
                        <div class="bg-white p-3 rounded border-l-4 border-green-500">
                            <label class="text-sm font-medium text-gray-600">Catatan Penutupan:</label>
                            <p class="text-gray-800 mt-1">{{ $audit->catatan_penutupan }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer Info -->
        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    <div><strong>Dibuat oleh:</strong> {{ $audit->pembuat }}</div>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <div><strong>Unit Kerja:</strong> {{ $audit->nama_unit }}</div>
                </div>
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div><strong>Status:</strong> {{ $audit->status }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
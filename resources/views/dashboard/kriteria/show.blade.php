@extends('layouts.app')

@section('title', 'Detail Kriteria')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <div class="mb-6">
        <a href="/dashboard/kriteria" class="text-blue-600 hover:text-blue-800">← Kembali</a>
    </div>

    <div class="grid grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-600 text-sm font-medium mb-1">Kode Kriteria</label>
            <p class="text-gray-900 font-semibold">{{ $kriteria->kode_kriteria }}</p>
        </div>

        <div>
            <label class="block text-gray-600 text-sm font-medium mb-1">Status</label>
            <span class="px-3 py-1 text-sm rounded-full bg-yellow-100 text-yellow-800">{{ $kriteria->status }}</span>
        </div>

        <div class="col-span-2">
            <label class="block text-gray-600 text-sm font-medium mb-1">Nama Kriteria</label>
            <p class="text-gray-900">{{ $kriteria->nama_kriteria }}</p>
        </div>

        <div class="col-span-2">
            <label class="block text-gray-600 text-sm font-medium mb-1">Standar Mutu</label>
            <p class="text-gray-900">{{ $kriteria->kode_standar }} - {{ $kriteria->nama_standar }}</p>
        </div>

        <div>
            <label class="block text-gray-600 text-sm font-medium mb-1">Tahun Berlaku</label>
            <p class="text-gray-900">{{ $kriteria->tahun_berlaku }}</p>
        </div>

        <div>
            <label class="block text-gray-600 text-sm font-medium mb-1">Tanggal Dibuat</label>
            <p class="text-gray-900">{{ date('d/m/Y H:i', strtotime($kriteria->tanggal_dibuat)) }}</p>
        </div>

        <div class="col-span-2">
            <label class="block text-gray-600 text-sm font-medium mb-1">Deskripsi</label>
            <p class="text-gray-900">{{ $kriteria->deskripsi ?? '-' }}</p>
        </div>

        <div>
            <label class="block text-gray-600 text-sm font-medium mb-1">Dibuat Oleh</label>
            <p class="text-gray-900">{{ $kriteria->pembuat }}</p>
        </div>

        @if($kriteria->penyetuju)
        <div>
            <label class="block text-gray-600 text-sm font-medium mb-1">Disetujui Oleh</label>
            <p class="text-gray-900">{{ $kriteria->penyetuju }}</p>
        </div>

        <div>
            <label class="block text-gray-600 text-sm font-medium mb-1">Tanggal Disetujui</label>
            <p class="text-gray-900">{{ date('d/m/Y', strtotime($kriteria->tanggal_disetujui)) }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

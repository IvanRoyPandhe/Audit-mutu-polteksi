@extends('layouts.app')

@section('title', 'Edit Kriteria')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/kriteria/{{ $kriteria->kriteria_id }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Standar Mutu</label>
            <select name="standar_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="">Pilih Standar Mutu</option>
                @foreach($standar as $item)
                <option value="{{ $item->standar_id }}" {{ $kriteria->standar_id == $item->standar_id ? 'selected' : '' }}>{{ $item->kode_standar }} - {{ $item->nama_standar }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Kode Kriteria</label>
            <input type="text" name="kode_kriteria" value="{{ $kriteria->kode_kriteria }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Nama Kriteria</label>
            <input type="text" name="nama_kriteria" value="{{ $kriteria->nama_kriteria }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tahun Berlaku</label>
            <input type="number" name="tahun_berlaku" value="{{ $kriteria->tahun_berlaku }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ $kriteria->deskripsi }}</textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
            <a href="/dashboard/kriteria" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

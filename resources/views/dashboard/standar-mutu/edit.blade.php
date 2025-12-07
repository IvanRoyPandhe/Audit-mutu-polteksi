@extends('layouts.app')

@section('title', 'Edit Standar Mutu')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/standar-mutu/{{ $standar->standar_id }}">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Kode Standar</label>
            <input type="text" name="kode_standar" value="{{ $standar->kode_standar }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('kode_standar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Nama Standar</label>
            <input type="text" name="nama_standar" value="{{ $standar->nama_standar }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('nama_standar')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ $standar->deskripsi }}</textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
            <a href="/dashboard/standar-mutu" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

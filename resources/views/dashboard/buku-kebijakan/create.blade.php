@extends('layouts.app')

@section('title', 'Tambah ' . ucfirst($tipe))

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/buku-kebijakan">
        @csrf
        <input type="hidden" name="tipe" value="{{ $tipe }}">
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Judul {{ ucfirst($tipe) }}</label>
            <input type="text" name="judul" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Link Dokumen</label>
            <input type="url" name="link" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="https://..." required>
            @error('link')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="/dashboard/{{ $tipe }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection
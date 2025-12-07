@extends('layouts.app')

@section('title', 'Tambah Penetapan')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/penetapan">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Kriteria</label>
            <select name="kriteria_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="">Pilih Kriteria</option>
                @foreach($kriteria as $item)
                <option value="{{ $item->kriteria_id }}">{{ $item->kode_kriteria }} - {{ $item->nama_kriteria }}</option>
                @endforeach
            </select>
            @error('kriteria_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            <p class="text-xs text-gray-500 mt-1">Semua indikator dari kriteria ini akan ditetapkan</p>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tahun</label>
            <input type="number" name="tahun" value="{{ date('Y') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('tahun')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Target Capaian</label>
            <div id="targetContainer">
                <div class="flex gap-2 mb-2">
                    <input type="text" name="target_capaian[]" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" placeholder="Target capaian 1" required>
                    <button type="button" onclick="removeTarget(this)" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 hidden">Hapus</button>
                </div>
            </div>
            <button type="button" onclick="addTarget()" class="text-blue-600 hover:text-blue-800 text-sm">+ Tambah Target</button>
            @error('target_capaian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Anggaran</label>
            <input type="number" name="anggaran" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            @error('anggaran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="/dashboard/penetapan" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
let targetCount = 1;

function addTarget() {
    targetCount++;
    const container = document.getElementById('targetContainer');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="target_capaian[]" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" placeholder="Target capaian ${targetCount}" required>
        <button type="button" onclick="removeTarget(this)" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600">Hapus</button>
    `;
    container.appendChild(div);
}

function removeTarget(btn) {
    btn.parentElement.remove();
}
</script>
@endsection

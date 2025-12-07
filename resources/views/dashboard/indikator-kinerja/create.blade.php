@extends('layouts.app')

@section('title', 'Tambah Indikator Kinerja')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/indikator-kinerja">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Kriteria</label>
            <select name="kriteria_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="">Pilih Kriteria</option>
                @foreach($kriteria as $item)
                <option value="{{ $item->kriteria_id }}">{{ $item->kode_kriteria }} - {{ $item->nama_kriteria }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Kode Indikator</label>
            <input type="text" name="kode_indikator" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="IK-001.01.01" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Nama Indikator</label>
            <input type="text" name="nama_indikator" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Target</label>
            <div id="target-container">
                <div class="flex gap-2 mb-2">
                    <input type="text" name="target[]" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" placeholder="Masukkan target" required>
                    <button type="button" onclick="removeTarget(this)" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 hidden">Hapus</button>
                </div>
            </div>
            <button type="button" onclick="addTarget()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm">+ Tambah Target</button>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="/dashboard/indikator-kinerja" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
function addTarget() {
    const container = document.getElementById('target-container');
    const div = document.createElement('div');
    div.className = 'flex gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="target[]" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" placeholder="Masukkan target" required>
        <button type="button" onclick="removeTarget(this)" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">Hapus</button>
    `;
    container.appendChild(div);
}

function removeTarget(button) {
    button.parentElement.remove();
}
</script>
@endsection

@extends('layouts.app')

@section('title', 'Tambah Pelaksanaan')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/pelaksanaan">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Penetapan</label>
            <select name="penetapan_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="">Pilih Penetapan</option>
                @foreach($penetapan as $item)
                <option value="{{ $item->penetapan_id }}">{{ $item->nama_kriteria }} - {{ $item->nama_indikator }} ({{ $item->tahun }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Dokumen</label>
            <div id="dokumenContainer">
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <input type="text" name="dokumen_judul[]" class="px-4 py-2 border border-gray-300 rounded-lg" placeholder="Judul dokumen">
                    <div class="flex gap-2">
                        <input type="url" name="dokumen_url[]" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" placeholder="URL dokumen">
                        <button type="button" onclick="removeDokumen(this)" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 hidden">Hapus</button>
                    </div>
                </div>
            </div>
            <button type="button" onclick="addDokumen()" class="text-blue-600 hover:text-blue-800 text-sm">+ Tambah Dokumen</button>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="/dashboard/pelaksanaan" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
let dokumenCount = 1;

function addDokumen() {
    dokumenCount++;
    const container = document.getElementById('dokumenContainer');
    const div = document.createElement('div');
    div.className = 'grid grid-cols-2 gap-2 mb-2';
    div.innerHTML = `
        <input type="text" name="dokumen_judul[]" class="px-4 py-2 border border-gray-300 rounded-lg" placeholder="Judul dokumen">
        <div class="flex gap-2">
            <input type="url" name="dokumen_url[]" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" placeholder="URL dokumen">
            <button type="button" onclick="removeDokumen(this)" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600">Hapus</button>
        </div>
    `;
    container.appendChild(div);
}

function removeDokumen(btn) {
    btn.closest('.grid').remove();
}
</script>
@endsection

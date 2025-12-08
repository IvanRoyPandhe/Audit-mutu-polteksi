@extends('layouts.app')

@section('title', 'Edit Pelaksanaan')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/pelaksanaan/{{ $pelaksanaan->pelaksanaan_id }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Kriteria</label>
            <input type="text" value="{{ $pelaksanaan->nama_kriteria }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Indikator</label>
            <input type="text" value="{{ $pelaksanaan->nama_indikator }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tahun</label>
            <input type="text" value="{{ $pelaksanaan->tahun }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Mulai</label>
            <input type="date" name="tanggal_mulai" value="{{ $pelaksanaan->tanggal_mulai }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('tanggal_mulai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Selesai</label>
            <input type="date" name="tanggal_selesai" value="{{ $pelaksanaan->tanggal_selesai }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            @error('tanggal_selesai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">PIC (Penanggung Jawab)</label>
            <input type="text" name="pic" value="{{ $pelaksanaan->pic }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Nama PIC">
            @error('pic')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Dokumen</label>
            <div id="dokumenContainer">
                @php
                    $dokumen = json_decode($pelaksanaan->dokumen_link, true);
                    if (!is_array($dokumen) || empty($dokumen)) {
                        $dokumen = [['judul' => '', 'url' => '']];
                    }
                @endphp
                @foreach($dokumen as $index => $dok)
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <input type="text" name="dokumen_judul[]" value="{{ $dok['judul'] ?? '' }}" class="px-4 py-2 border border-gray-300 rounded-lg" placeholder="Judul dokumen">
                    <div class="flex gap-2">
                        <input type="url" name="dokumen_url[]" value="{{ $dok['url'] ?? '' }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" placeholder="URL dokumen">
                        @if($index > 0)
                        <button type="button" onclick="removeDokumen(this)" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600">Hapus</button>
                        @else
                        <button type="button" onclick="removeDokumen(this)" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 hidden">Hapus</button>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <button type="button" onclick="addDokumen()" class="text-blue-600 hover:text-blue-800 text-sm">+ Tambah Dokumen</button>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Keterangan</label>
            <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ $pelaksanaan->keterangan }}</textarea>
            @error('keterangan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Status</label>
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="Belum Dimulai" {{ $pelaksanaan->status == 'Belum Dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
                <option value="Sedang Berjalan" {{ $pelaksanaan->status == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                <option value="Selesai" {{ $pelaksanaan->status == 'Selesai' ? 'selected' : '' }}>Selesai</option>
            </select>
            @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
            <a href="/dashboard/pelaksanaan" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
let dokumenCount = {{ count($dokumen) }};

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

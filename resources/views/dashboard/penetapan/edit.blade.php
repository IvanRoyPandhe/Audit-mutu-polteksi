@extends('layouts.app')

@section('title', 'Edit Penetapan')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/penetapan/{{ $penetapan->penetapan_id }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Kriteria</label>
            <input type="text" value="{{ $penetapan->nama_kriteria }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Indikator</label>
            <input type="text" value="{{ $penetapan->nama_indikator }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tahun</label>
            <input type="number" name="tahun" value="{{ $penetapan->tahun }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('tahun')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Target Capaian</label>
            <div id="targetContainer">
                @php
                    $targets = json_decode($penetapan->target_capaian, true);
                    if (!is_array($targets)) {
                        $targets = [$penetapan->target_capaian];
                    }
                @endphp
                @foreach($targets as $index => $target)
                <div class="flex gap-2 mb-2">
                    <input type="text" name="target_capaian[]" value="{{ $target }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg" placeholder="Target capaian {{ $index + 1 }}" required>
                    @if($index > 0)
                    <button type="button" onclick="removeTarget(this)" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600">Hapus</button>
                    @else
                    <button type="button" onclick="removeTarget(this)" class="bg-red-500 text-white px-3 py-2 rounded-lg hover:bg-red-600 hidden">Hapus</button>
                    @endif
                </div>
                @endforeach
            </div>
            <button type="button" onclick="addTarget()" class="text-blue-600 hover:text-blue-800 text-sm">+ Tambah Target</button>
            @error('target_capaian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Anggaran</label>
            <input type="number" name="anggaran" value="{{ $penetapan->anggaran }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            @error('anggaran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">PIC (Penanggung Jawab)</label>
            <input type="text" name="pic" value="{{ $penetapan->pic }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Nama PIC">
            @error('pic')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Rencana Mulai</label>
            <input type="date" name="tanggal_rencana_mulai" value="{{ $penetapan->tanggal_rencana_mulai }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            @error('tanggal_rencana_mulai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Rencana Selesai</label>
            <input type="date" name="tanggal_rencana_selesai" value="{{ $penetapan->tanggal_rencana_selesai }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            @error('tanggal_rencana_selesai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
            <a href="/dashboard/penetapan" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
let targetCount = {{ count($targets) }};

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

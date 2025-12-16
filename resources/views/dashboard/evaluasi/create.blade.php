@extends('layouts.app')

@section('title', 'Tambah Evaluasi')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/evaluasi">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Pelaksanaan</label>
            <select name="pelaksanaan_id" id="pelaksanaan_select" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required onchange="updateAuditors()">
                <option value="">Pilih Pelaksanaan</option>
                @foreach($pelaksanaan as $item)
                @php
                    $unitName = DB::table('users')
                        ->join('unit', 'users.unit_id', '=', 'unit.unit_id')
                        ->where('users.user_id', $item->dibuat_oleh)
                        ->value('unit.nama_unit');
                @endphp
                <option value="{{ $item->pelaksanaan_id }}" data-auditors="{{ json_encode($item->auditors) }}" {{ request('pelaksanaan_id') == $item->pelaksanaan_id ? 'selected' : '' }}>
                    [{{ $unitName }}] {{ $item->nama_kriteria }} - {{ $item->nama_indikator }} ({{ $item->tahun }})
                </option>
                @endforeach
            </select>
            @error('pelaksanaan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Auditor</label>
            <select name="auditor_id" id="auditor_select" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="">Pilih Pelaksanaan terlebih dahulu</option>
            </select>
            @error('auditor_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Audit</label>
            <input type="date" name="tanggal_audit" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('tanggal_audit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Evaluasi Kesesuaian</label>
            <select name="evaluasi_kesesuaian" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="">Pilih Evaluasi</option>
                <option value="Sesuai">Sesuai</option>
                <option value="Tidak Sesuai">Tidak Sesuai</option>
                <option value="Menyimpang">Menyimpang</option>
                <option value="Melampaui">Melampaui</option>
            </select>
            @error('evaluasi_kesesuaian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Hasil Audit</label>
            <select name="hasil_audit" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="">Pilih Hasil Audit</option>
                <option value="Tidak Ada">Tidak Ada</option>
                <option value="Minor">Minor</option>
                <option value="Mayor">Mayor</option>
                <option value="Observasi">Observasi</option>
            </select>
            @error('hasil_audit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Rekomendasi Perbaikan</label>
            <textarea name="rekomendasi_perbaikan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
            @error('rekomendasi_perbaikan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Catatan Penutupan</label>
            <textarea name="catatan_penutupan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
            @error('catatan_penutupan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="/dashboard/evaluasi" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>

<script>
function updateAuditors() {
    const pelaksanaanSelect = document.getElementById('pelaksanaan_select');
    const auditorSelect = document.getElementById('auditor_select');
    const selectedOption = pelaksanaanSelect.options[pelaksanaanSelect.selectedIndex];
    
    auditorSelect.innerHTML = '<option value="">Pilih Auditor</option>';
    
    if (selectedOption.value) {
        const auditors = JSON.parse(selectedOption.getAttribute('data-auditors') || '[]');
        auditors.forEach(auditor => {
            const option = document.createElement('option');
            option.value = auditor.user_id;
            option.textContent = auditor.name;
            auditorSelect.appendChild(option);
        });
    }
}

// Auto-update auditors on page load if pelaksanaan is pre-selected
document.addEventListener('DOMContentLoaded', function() {
    updateAuditors();
});
</script>
@endsection
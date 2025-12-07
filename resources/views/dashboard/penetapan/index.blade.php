@extends('layouts.app')

@section('title', 'Penetapan')

@section('content')
@if(auth()->user()->role_id != 4)
<div class="mb-6 flex justify-between items-center">
    @if(auth()->user()->role_id == 3)
    <a href="/dashboard/penetapan/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Penetapan</a>
    @else
    <div></div>
    @endif
    
    <form method="GET" class="flex gap-2">
        @if(auth()->user()->role_id == 1)
        <select name="unit_id" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
            @endforeach
        </select>
        @endif
        
        <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Tahun</option>
            @foreach($tahun_list as $t)
            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        
        <select name="kriteria_id" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Kriteria</option>
            @foreach($kriteria as $k)
            <option value="{{ $k->kriteria_id }}" {{ request('kriteria_id') == $k->kriteria_id ? 'selected' : '' }}>{{ $k->nama_kriteria }}</option>
            @endforeach
        </select>
        
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Filter</button>
        <a href="/dashboard/penetapan" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Reset</a>
    </form>
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indikator</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target Capaian</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anggaran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                @if(auth()->user()->role_id == 1)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                @endif
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($penetapan as $item)
            <tr>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_indikator, 40) }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_kriteria, 30) }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->tahun }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->target_capaian, 30) }}</td>
                <td class="px-6 py-4 text-sm">Rp {{ number_format($item->anggaran, 0, ',', '.') }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $item->status }}</span>
                </td>
                @if(auth()->user()->role_id == 1)
                <td class="px-6 py-4 text-sm">{{ $item->nama_unit }}</td>
                @endif
                <td class="px-6 py-4 text-sm">
                    <button onclick="toggleDetail('penetapan-{{ $item->penetapan_id }}')" class="text-blue-600 hover:text-blue-800 mr-3">Detail</button>
                    @if(auth()->user()->role_id == 4 && $item->status == 'Draft')
                    <button onclick="showApproveModal('penetapan', {{ $item->penetapan_id }})" class="text-green-600 hover:text-green-800 mr-3">Setujui</button>
                    <button onclick="showRejectModal('penetapan', {{ $item->penetapan_id }})" class="text-red-600 hover:text-red-800">Tolak</button>
                    @endif
                    @if($item->dibuat_oleh == auth()->id() && auth()->user()->role_id != 2)
                        @if($item->status == 'Ditolak')
                        <form method="POST" action="/dashboard/penetapan/{{ $item->penetapan_id }}/resubmit" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 mr-3">Ajukan Kembali</button>
                        </form>
                        @endif
                        @if($item->status != 'Disetujui')
                        <a href="/dashboard/penetapan/{{ $item->penetapan_id }}/edit" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                        <form method="POST" action="/dashboard/penetapan/{{ $item->penetapan_id }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                        @endif
                    @endif
                </td>
            </tr>
            <tr id="penetapan-{{ $item->penetapan_id }}" class="hidden bg-gray-50">
                <td colspan="{{ auth()->user()->role_id == 1 ? '8' : '7' }}" class="px-6 py-4">
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div><strong>Kriteria:</strong> {{ $item->nama_kriteria }}</div>
                            <div><strong>Tahun:</strong> {{ $item->tahun }}</div>
                            <div class="col-span-2"><strong>Indikator:</strong> {{ $item->nama_indikator }}</div>
                            @php
                                $indikator = DB::table('indikator_kinerja')->where('indikator_id', $item->indikator_id)->first();
                                $targets = json_decode($indikator->target ?? '[]', true);
                            @endphp
                            @if(is_array($targets) && count($targets) > 0)
                            <div class="col-span-2">
                                <strong>Target Indikator:</strong>
                                <ul class="list-disc list-inside mt-1 ml-4">
                                    @foreach($targets as $t)
                                        <li>{{ $t }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="col-span-2">
                                <strong>Target Capaian:</strong>
                                @php
                                    $targetCapaian = $item->target_capaian;
                                    if ($item->status == 'Ditolak' && strpos($targetCapaian, 'Ditolak:') !== false) {
                                        $targetCapaian = substr($targetCapaian, 0, strpos($targetCapaian, "\n\nDitolak:"));
                                    }
                                    $targets = json_decode($targetCapaian, true);
                                @endphp
                                @if(is_array($targets))
                                    <ul class="list-disc list-inside mt-1 ml-4">
                                        @foreach($targets as $t)
                                            <li>{{ $t }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mt-1">{{ $targetCapaian }}</p>
                                @endif
                            </div>
                            <div><strong>Anggaran:</strong> Rp {{ number_format($item->anggaran, 0, ',', '.') }}</div>
                            <div><strong>Status:</strong> {{ $item->status }}</div>
                            @if($item->status == 'Ditolak' && strpos($item->target_capaian, 'Ditolak:') !== false)
                            <div class="col-span-2 bg-red-50 p-3 rounded">
                                <strong class="text-red-600">Alasan Ditolak:</strong>
                                <p class="text-red-600 text-sm mt-1">{{ trim(substr($item->target_capaian, strpos($item->target_capaian, 'Ditolak:') + 9)) }}</p>
                            </div>
                            @endif
                            <div><strong>Dibuat Oleh:</strong> {{ $item->pembuat }}</div>
                            @if(auth()->user()->role_id == 1)
                            <div><strong>Unit:</strong> {{ $item->nama_unit }}</div>
                            @endif
                            <div><strong>Tanggal Dibuat:</strong> {{ date('d/m/Y H:i', strtotime($item->tanggal_dibuat)) }}</div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->role_id == 1 ? '8' : '7' }}" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">Alasan Penolakan</h3>
        <form method="POST" action="/dashboard/approval/reject">
            @csrf
            <input type="hidden" name="type" id="rejectType">
            <input type="hidden" name="id" id="rejectId">
            <textarea name="alasan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Masukkan alasan penolakan..." required></textarea>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Tolak</button>
                <button type="button" onclick="closeRejectModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
            </div>
        </form>
    </div>
</div>

<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Persetujuan</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyetujui penetapan ini?</p>
        <form method="POST" action="/dashboard/approval" id="approveForm">
            @csrf
            <input type="hidden" name="type" id="approveType">
            <input type="hidden" name="id" id="approveId">
            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ya, Setujui</button>
                <button type="button" onclick="closeApproveModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleDetail(id) {
    const element = document.getElementById(id);
    element.classList.toggle('hidden');
}

function showApproveModal(type, id) {
    document.getElementById('approveType').value = type;
    document.getElementById('approveId').value = id;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function showRejectModal(type, id) {
    document.getElementById('rejectType').value = type;
    document.getElementById('rejectId').value = id;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>

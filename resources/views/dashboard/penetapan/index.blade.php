@extends('layouts.app')

@section('title', 'Penetapan')

@section('content')
@if(auth()->user()->role_id != 4)
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    @if(auth()->user()->role_id == 3)
    <a href="/dashboard/penetapan/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto text-center">+ Tambah Penetapan</a>
    @else
    <div></div>
    @endif
    
    <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
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

<div class="bg-white rounded-lg shadow overflow-x-auto">
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
            <tr id="penetapan-{{ $item->penetapan_id }}" class="hidden bg-gradient-to-r from-indigo-50 to-gray-50">
                <td colspan="{{ auth()->user()->role_id == 1 ? '8' : '7' }}" class="px-6 py-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <div><strong class="text-gray-700">Kriteria:</strong><br>{{ $item->nama_kriteria }}</div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div><strong class="text-gray-700">Tahun:</strong><br>{{ $item->tahun }}</div>
                            </div>
                            <div class="col-span-2 flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <div class="flex-1"><strong class="text-gray-700">Indikator:</strong><br>{{ $item->nama_indikator }}</div>
                            </div>
                            @php
                                $indikator = DB::table('indikator_kinerja')->where('indikator_id', $item->indikator_id)->first();
                                $targets = json_decode($indikator->target ?? '[]', true);
                            @endphp
                            @if(is_array($targets) && count($targets) > 0)
                            <div class="col-span-2 bg-purple-50 p-3 rounded-lg">
                                <strong class="text-gray-700 flex items-center"><svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>Target Indikator:</strong>
                                <ul class="list-none mt-2 space-y-1">
                                    @foreach($targets as $index => $t)
                                        <li class="flex items-start"><span class="text-purple-600 font-bold mr-2">{{ $index + 1 }}.</span><span>{{ $t }}</span></li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="col-span-2 bg-blue-50 p-3 rounded-lg">
                                <strong class="text-gray-700 flex items-center"><svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>Target Capaian:</strong>
                                @php
                                    $targetCapaian = $item->target_capaian;
                                    if ($item->status == 'Ditolak' && strpos($targetCapaian, 'Ditolak:') !== false) {
                                        $targetCapaian = substr($targetCapaian, 0, strpos($targetCapaian, "\n\nDitolak:"));
                                    }
                                    $targets = json_decode($targetCapaian, true);
                                @endphp
                                @if(is_array($targets))
                                    <ul class="list-none mt-2 space-y-1">
                                        @foreach($targets as $index => $t)
                                            <li class="flex items-start"><span class="text-blue-600 font-bold mr-2">{{ $index + 1 }}.</span><span>{{ $t }}</span></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mt-1">{{ $targetCapaian }}</p>
                                @endif
                            </div>
                            <div class="flex items-start bg-green-50 p-3 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div><strong class="text-gray-700">Anggaran:</strong><br>Rp {{ number_format($item->anggaran, 0, ',', '.') }}</div>
                            </div>
                            <div class="flex items-start bg-gray-50 p-3 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div><strong class="text-gray-700">Status:</strong><br>
                                    <span class="inline-block px-2 py-1 text-xs rounded-full mt-1
                                        @if($item->status == 'Disetujui') bg-green-100 text-green-800
                                        @elseif($item->status == 'Ditolak') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $item->status }}
                                    </span>
                                </div>
                            </div>
                            @if($item->status == 'Ditolak' && strpos($item->target_capaian, 'Ditolak:') !== false)
                            <div class="col-span-2 bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                <strong class="text-red-700 flex items-center"><svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>Alasan Ditolak:</strong>
                                <p class="text-red-600 text-sm mt-1 ml-7">{{ trim(substr($item->target_capaian, strpos($item->target_capaian, 'Ditolak:') + 9)) }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div><strong class="text-gray-600">Dibuat:</strong> {{ $item->pembuat }}</div>
                            </div>
                            @if(auth()->user()->role_id == 1)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <div><strong class="text-gray-600">Unit:</strong> {{ $item->nama_unit }}</div>
                            </div>
                            @endif
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div><strong class="text-gray-600">Dibuat:</strong> {{ date('d/m/Y H:i', strtotime($item->tanggal_dibuat)) }}</div>
                            </div>
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

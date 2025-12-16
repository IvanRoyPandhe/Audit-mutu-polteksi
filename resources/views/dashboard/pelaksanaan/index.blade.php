@extends('layouts.app')

@section('title', 'Pelaksanaan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    @if(auth()->user()->role_id == 2)
    <div class="text-sm text-gray-600">Menampilkan pelaksanaan yang sudah selesai</div>
    @else
    <div class="text-sm text-gray-600">Pelaksanaan otomatis dibuat saat penetapan disetujui</div>
    @endif
    
    <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 4)
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
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Status</option>
            <option value="Belum Dimulai" {{ request('status') == 'Belum Dimulai' ? 'selected' : '' }}>Belum Dimulai</option>
            <option value="Sedang Berjalan" {{ request('status') == 'Sedang Berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
            <option value="Selesai" {{ request('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Filter</button>
        <a href="/dashboard/pelaksanaan" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Reset</a>
    </form>
</div>

@if(auth()->user()->role_id == 1)
<div class="mb-4">
    <a href="/dashboard/unit-auditors" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Kelola Auditor Unit</a>
</div>
@endif

@forelse($pelaksanaan as $unitName => $items)
<div class="mb-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-800 bg-blue-50 px-4 py-2 rounded-lg">{{ $unitName }}</h3>
        @if(auth()->user()->role_id == 1)
        <div class="text-sm text-gray-600">
            Auditor: 
            @php
                $unitAuditors = DB::table('unit_auditors')
                    ->join('users', 'unit_auditors.auditor_id', '=', 'users.user_id')
                    ->join('unit', 'unit_auditors.unit_id', '=', 'unit.unit_id')
                    ->where('unit.nama_unit', $unitName)
                    ->pluck('users.name');
            @endphp
            @if($unitAuditors->count() > 0)
                {{ $unitAuditors->implode(', ') }}
            @else
                <span class="text-red-500">Belum ada auditor</span>
            @endif
        </div>
        @endif
    </div>
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria & Indikator</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Periode</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auditor & Evaluasi</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($items as $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($item->nama_kriteria, 25) }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($item->nama_indikator, 30) }}</div>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-900">{{ $item->tahun }}</td>
                    <td class="px-4 py-3 text-sm text-gray-900">
                        <div>{{ date('d/m/Y', strtotime($item->tanggal_mulai)) }}</div>
                        <div class="text-xs text-gray-500">s/d {{ $item->tanggal_selesai ? date('d/m/Y', strtotime($item->tanggal_selesai)) : '-' }}</div>
                    </td>
                    <td class="px-4 py-3">
                        @if($item->status == 'Belum Dimulai')
                        <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $item->status }}</span>
                        @elseif($item->status == 'Sedang Berjalan')
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $item->status }}</span>
                        @else
                        <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $item->status }}</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @php
                            $unitId = DB::table('users')->where('user_id', $item->dibuat_oleh)->value('unit_id');
                            $unitAuditors = DB::table('unit_auditors')
                                ->join('users', 'unit_auditors.auditor_id', '=', 'users.user_id')
                                ->where('unit_auditors.unit_id', $unitId)
                                ->select('users.name', 'users.user_id')
                                ->get();
                            $totalAuditors = $unitAuditors->count();
                            $completedAudits = DB::table('audit')
                                ->whereIn('auditor_id', $unitAuditors->pluck('user_id'))
                                ->where('pelaksanaan_id', $item->pelaksanaan_id)
                                ->count();
                            $hasEvaluation = $completedAudits > 0;
                            $allAuditorsCompleted = $completedAudits >= $totalAuditors;
                        @endphp
                        @if($unitAuditors->count() > 0)
                            <div class="space-y-1">
                                @foreach($unitAuditors as $auditor)
                                <div class="text-xs bg-blue-50 px-2 py-1 rounded text-blue-700">{{ $auditor->name }}</div>
                                @endforeach
                            </div>
                            @if($item->status == 'Selesai')
                                @if(!$hasEvaluation)
                                <a href="/dashboard/evaluasi/create?pelaksanaan_id={{ $item->pelaksanaan_id }}" class="text-xs bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700 mt-1 inline-block">+ Evaluasi</a>
                                @elseif($allAuditorsCompleted)
                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded mt-1 inline-block">✓ Semua auditor selesai</span>
                                @else
                                <div class="mt-1">
                                    <a href="/dashboard/evaluasi/create?pelaksanaan_id={{ $item->pelaksanaan_id }}" class="text-xs bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 inline-block">+ Evaluasi</a>
                                    <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-1 rounded inline-block">{{ $completedAudits }}/{{ $totalAuditors }} selesai</span>
                                </div>
                                @endif
                            @endif
                        @else
                            <span class="text-xs text-red-500">Belum ada auditor</span>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex flex-col gap-1">
                            <button onclick="toggleDetail('pelaksanaan-{{ $item->pelaksanaan_id }}')" class="text-blue-600 hover:text-blue-800 text-xs">Detail</button>
                            @if($item->dibuat_oleh == auth()->id() && auth()->user()->role_id != 2)
                            <a href="/dashboard/pelaksanaan/{{ $item->pelaksanaan_id }}/edit" class="text-yellow-600 hover:text-yellow-800 text-xs">Edit</a>
                            @endif
                        </div>
                    </td>
                </tr>
                <tr id="pelaksanaan-{{ $item->pelaksanaan_id }}" class="hidden bg-gray-50">
                    <td colspan="6" class="px-4 py-4">
                        <div class="bg-white rounded p-4 text-sm">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-3">
                                    <div class="bg-blue-50 p-3 rounded">
                                        <strong class="text-blue-800">Kriteria:</strong>
                                        <div class="text-blue-700">{{ $item->nama_kriteria }}</div>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded">
                                        <strong class="text-green-800">Indikator:</strong>
                                        <div class="text-green-700">{{ $item->nama_indikator }}</div>
                                    </div>
                                    <div class="bg-purple-50 p-3 rounded">
                                        <strong class="text-purple-800">Target Capaian:</strong>
                                        @php $targets = json_decode($item->target_capaian, true); @endphp
                                        @if(is_array($targets))
                                            <ul class="list-disc list-inside text-purple-700 mt-1">
                                                @foreach($targets as $t)
                                                <li>{{ $t }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <div class="text-purple-700">{{ $item->target_capaian }}</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="bg-yellow-50 p-3 rounded">
                                        <strong class="text-yellow-800">Periode Pelaksanaan:</strong>
                                        <div class="text-yellow-700">
                                            {{ date('d/m/Y', strtotime($item->tanggal_mulai)) }} - 
                                            {{ $item->tanggal_selesai ? date('d/m/Y', strtotime($item->tanggal_selesai)) : 'Belum ditentukan' }}
                                        </div>
                                    </div>
                                    <div class="bg-green-50 p-3 rounded">
                                        <strong class="text-green-800">Tanggal Realisasi:</strong>
                                        <div class="text-green-700">{{ $item->tanggal_realisasi ? date('d/m/Y', strtotime($item->tanggal_realisasi)) : 'Belum direalisasi' }}</div>
                                    </div>
                                    <div class="bg-gray-50 p-3 rounded">
                                        <strong class="text-gray-800">Anggaran:</strong>
                                        <div class="text-gray-700">Rp {{ number_format($item->anggaran ?? 0, 0, ',', '.') }}</div>
                                    </div>
                                    @if($item->pic)
                                    <div class="bg-indigo-50 p-3 rounded">
                                        <strong class="text-indigo-800">PIC:</strong>
                                        <div class="text-indigo-700">{{ $item->pic }}</div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="bg-orange-50 p-3 rounded">
                                    <strong class="text-orange-800">Dokumen:</strong>
                                    @if($item->dokumen_link)
                                        @php $dokumen = json_decode($item->dokumen_link, true); @endphp
                                        @if(is_array($dokumen))
                                            <div class="mt-2 space-y-1">
                                                @foreach($dokumen as $dok)
                                                <a href="{{ $dok['url'] }}" target="_blank" class="flex items-center text-orange-700 hover:text-orange-900 underline">
                                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z"/>
                                                    </svg>
                                                    {{ $dok['judul'] }}
                                                </a>
                                                @endforeach
                                            </div>
                                        @else
                                            <a href="{{ $item->dokumen_link }}" target="_blank" class="flex items-center text-orange-700 hover:text-orange-900 underline mt-1">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm0 2h12v8H4V6z"/>
                                                </svg>
                                                Lihat Dokumen
                                            </a>
                                        @endif
                                    @else
                                        <div class="text-orange-600 mt-1 italic">Belum ada dokumen</div>
                                    @endif
                                </div>
                                <div class="bg-teal-50 p-3 rounded">
                                    <strong class="text-teal-800">Keterangan:</strong>
                                    <div class="text-teal-700 mt-1">{{ $item->keterangan ?: 'Tidak ada keterangan tambahan' }}</div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@empty
<div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
    Tidak ada data
</div>
@endforelse

<script>
function toggleDetail(id) {
    const element = document.getElementById(id);
    element.classList.toggle('hidden');
}
</script>
@endsection
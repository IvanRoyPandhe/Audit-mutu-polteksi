@extends('layouts.app')

@section('title', 'Indikator Kinerja')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <a href="/dashboard/indikator-kinerja/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto text-center">+ Tambah Indikator Kinerja</a>
    
    <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        @if(auth()->user()->role_id == 1)
        <select name="unit_id" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
            @endforeach
        </select>
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Status</option>
            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
            <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
        </select>
        @endif
        <select name="kriteria_id" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Kriteria</option>
            @foreach($kriteria_list as $k)
            <option value="{{ $k->kriteria_id }}" {{ request('kriteria_id') == $k->kriteria_id ? 'selected' : '' }}>{{ $k->nama_kriteria }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Filter</button>
        <a href="/dashboard/indikator-kinerja" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Reset</a>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Indikator</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Target</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($indikator as $item)
            <tr>
                <td class="px-6 py-4 text-sm font-medium">{{ $item->kode_indikator }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_indikator, 50) }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_kriteria, 30) }}</td>
                <td class="px-6 py-4 text-sm">
                    @php
                        $targets = json_decode($item->target, true);
                    @endphp
                    @if(is_array($targets))
                        {{ count($targets) }} target
                    @else
                        {{ Str::limit($item->target, 30) }}
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $item->status }}</span>
                </td>
                <td class="px-6 py-4 text-sm">
                    <button onclick="toggleDetail('indikator-{{ $item->indikator_id }}')" class="text-blue-600 hover:text-blue-800 mr-3">Detail</button>
                    @if($item->dibuat_oleh == auth()->id())
                        @if($item->status == 'Ditolak')
                        <form method="POST" action="/dashboard/indikator-kinerja/{{ $item->indikator_id }}/resubmit" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 mr-3">Ajukan Kembali</button>
                        </form>
                        @endif
                        @if($item->status != 'Disetujui')
                        <a href="/dashboard/indikator-kinerja/{{ $item->indikator_id }}/edit" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                        <form method="POST" action="/dashboard/indikator-kinerja/{{ $item->indikator_id }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                        @endif
                    @endif
                </td>
            </tr>
            <tr id="indikator-{{ $item->indikator_id }}" class="hidden bg-gradient-to-r from-purple-50 to-gray-50">
                <td colspan="6" class="px-6 py-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                <div><strong class="text-gray-700">Kriteria:</strong><br>{{ $item->kode_kriteria }} - {{ $item->nama_kriteria }}</div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                <div><strong class="text-gray-700">Status:</strong><br>
                                    <span class="inline-block px-2 py-1 text-xs rounded-full mt-1
                                        @if($item->status == 'Disetujui') bg-green-100 text-green-800
                                        @elseif($item->status == 'Ditolak') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $item->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-span-2 bg-blue-50 p-3 rounded-lg">
                                <strong class="text-gray-700 flex items-center"><svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>Target:</strong>
                                @php
                                    $targets = json_decode($item->target, true);
                                @endphp
                                @if(is_array($targets))
                                    <ul class="list-none mt-2 space-y-1">
                                        @foreach($targets as $index => $t)
                                            <li class="flex items-start"><span class="text-blue-600 font-bold mr-2">{{ $index + 1 }}.</span><span>{{ $t }}</span></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mt-1">{{ $item->target }}</p>
                                @endif
                            </div>
                            @if($item->status == 'Ditolak')
                            <div class="col-span-2 bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                <strong class="text-red-700 flex items-center"><svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>Alasan Ditolak:</strong>
                                <p class="text-red-600 text-sm mt-1 ml-7">
                                    @if($item->keterangan && strpos($item->keterangan, 'Ditolak:') !== false)
                                        {{ substr($item->keterangan, strpos($item->keterangan, 'Ditolak:') + 9) }}
                                    @else
                                        Data ini ditolak. Silakan edit dan ajukan kembali.
                                    @endif
                                </p>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div><strong class="text-gray-600">Dibuat:</strong> {{ $item->pembuat }} <span class="text-gray-400">•</span> {{ date('d/m/Y H:i', strtotime($item->tanggal_dibuat)) }}</div>
                            </div>
                            @if($item->penyetuju)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div><strong class="text-gray-600">Disetujui:</strong> {{ $item->penyetuju }} <span class="text-gray-400">•</span> {{ date('d/m/Y', strtotime($item->tanggal_disetujui)) }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

<script>
function toggleDetail(id) {
    const element = document.getElementById(id);
    element.classList.toggle('hidden');
}
</script>

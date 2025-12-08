@extends('layouts.app')

@section('title', 'Kriteria')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <a href="/dashboard/kriteria/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto text-center">+ Tambah Kriteria</a>
    
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
        <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Tahun</option>
            @foreach($tahun_list as $t)
            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <select name="standar_id" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Standar</option>
            @foreach($standar_list as $s)
            <option value="{{ $s->standar_id }}" {{ request('standar_id') == $s->standar_id ? 'selected' : '' }}>{{ $s->nama_standar }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Filter</button>
        <a href="/dashboard/kriteria" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Reset</a>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Standar Mutu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembuat</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($kriteria as $item)
            <tr>
                <td class="px-6 py-4 text-sm font-medium">{{ $item->kode_kriteria }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_kriteria, 50) }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_standar, 30) }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->tahun_berlaku }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $item->status }}</span>
                </td>
                <td class="px-6 py-4 text-sm">{{ $item->pembuat }}</td>
                <td class="px-6 py-4 text-sm">
                    <button onclick="toggleDetail('kriteria-{{ $item->kriteria_id }}')" class="text-blue-600 hover:text-blue-800 mr-3">Detail</button>
                    @if($item->dibuat_oleh == auth()->id())
                        @if($item->status == 'Ditolak')
                        <form method="POST" action="/dashboard/kriteria/{{ $item->kriteria_id }}/resubmit" class="inline">
                            @csrf
                            <button type="submit" class="text-green-600 hover:text-green-800 mr-3">Ajukan Kembali</button>
                        </form>
                        @endif
                        @if($item->status != 'Disetujui')
                        <a href="/dashboard/kriteria/{{ $item->kriteria_id }}/edit" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                        <form method="POST" action="/dashboard/kriteria/{{ $item->kriteria_id }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                        </form>
                        @endif
                    @endif
                </td>
            </tr>
            <tr id="kriteria-{{ $item->kriteria_id }}" class="hidden bg-gradient-to-r from-blue-50 to-gray-50">
                <td colspan="7" class="px-6 py-4">
                    <div class="bg-white rounded-lg p-4 shadow-sm">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <div><strong class="text-gray-700">Standar Mutu:</strong><br>{{ $item->kode_standar }} - {{ $item->nama_standar }}</div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div><strong class="text-gray-700">Tahun Berlaku:</strong><br>{{ $item->tahun_berlaku }}</div>
                            </div>
                            <div class="col-span-2 flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                <div class="flex-1"><strong class="text-gray-700">Deskripsi:</strong><br>{{ $item->deskripsi ?? '-' }}</div>
                            </div>
                            @if($item->status == 'Ditolak' && strpos($item->deskripsi, 'Ditolak:') !== false)
                            <div class="col-span-2 bg-red-50 border-l-4 border-red-500 p-3 rounded">
                                <strong class="text-red-700 flex items-center"><svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>Alasan Ditolak:</strong>
                                <p class="text-red-600 text-sm mt-1 ml-7">{{ substr($item->deskripsi, strpos($item->deskripsi, 'Ditolak:') + 9) }}</p>
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
                <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
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

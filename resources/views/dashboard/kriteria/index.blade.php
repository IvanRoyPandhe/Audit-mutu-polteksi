@extends('layouts.app')

@section('title', 'Kriteria')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="/dashboard/kriteria/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Kriteria</a>
    
    <form method="GET" class="flex gap-2">
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

<div class="bg-white rounded-lg shadow overflow-hidden">
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
            <tr id="kriteria-{{ $item->kriteria_id }}" class="hidden bg-gray-50">
                <td colspan="7" class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><strong>Standar Mutu:</strong> {{ $item->kode_standar }} - {{ $item->nama_standar }}</div>
                        <div><strong>Tahun Berlaku:</strong> {{ $item->tahun_berlaku }}</div>
                        <div class="col-span-2"><strong>Deskripsi:</strong> {{ $item->deskripsi ?? '-' }}</div>
                        @if($item->status == 'Ditolak' && strpos($item->deskripsi, 'Ditolak:') !== false)
                        <div class="col-span-2 bg-red-50 p-3 rounded">
                            <strong class="text-red-600">Alasan Ditolak:</strong>
                            <p class="text-red-600 text-sm mt-1">{{ substr($item->deskripsi, strpos($item->deskripsi, 'Ditolak:') + 9) }}</p>
                        </div>
                        @endif
                        <div><strong>Dibuat Oleh:</strong> {{ $item->pembuat }}</div>
                        <div><strong>Tanggal Dibuat:</strong> {{ date('d/m/Y H:i', strtotime($item->tanggal_dibuat)) }}</div>
                        @if($item->penyetuju)
                        <div><strong>Disetujui Oleh:</strong> {{ $item->penyetuju }}</div>
                        <div><strong>Tanggal Disetujui:</strong> {{ date('d/m/Y', strtotime($item->tanggal_disetujui)) }}</div>
                        @endif
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

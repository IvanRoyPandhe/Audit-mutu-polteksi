@extends('layouts.app')

@section('title', 'Pelaksanaan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    @if(auth()->user()->role_id == 3)
    <a href="/dashboard/pelaksanaan/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Pelaksanaan</a>
    @elseif(auth()->user()->role_id == 2)
    <div class="text-sm text-gray-600">Menampilkan pelaksanaan yang sudah selesai</div>
    @else
    <div></div>
    @endif
    
    <form method="GET" class="flex gap-2">
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

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indikator</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 4)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                @endif
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($pelaksanaan as $item)
            <tr>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_kriteria, 30) }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_indikator, 40) }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->tahun }}</td>
                <td class="px-6 py-4 text-sm">{{ date('d/m/Y', strtotime($item->tanggal_mulai)) }} - {{ $item->tanggal_selesai ? date('d/m/Y', strtotime($item->tanggal_selesai)) : '-' }}</td>
                <td class="px-6 py-4 text-sm">
                    @if($item->status == 'Belum Dimulai')
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $item->status }}</span>
                    @elseif($item->status == 'Sedang Berjalan')
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $item->status }}</span>
                    @else
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $item->status }}</span>
                    @endif
                </td>
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 4)
                <td class="px-6 py-4 text-sm">{{ $item->nama_unit }}</td>
                @endif
                <td class="px-6 py-4 text-sm">
                    <button onclick="toggleDetail('pelaksanaan-{{ $item->pelaksanaan_id }}')" class="text-blue-600 hover:text-blue-800 mr-3">Detail</button>
                    @if($item->dibuat_oleh == auth()->id() && auth()->user()->role_id != 2)
                    <a href="/dashboard/pelaksanaan/{{ $item->pelaksanaan_id }}/edit" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                    <form method="POST" action="/dashboard/pelaksanaan/{{ $item->pelaksanaan_id }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            <tr id="pelaksanaan-{{ $item->pelaksanaan_id }}" class="hidden bg-gray-50">
                <td colspan="{{ auth()->user()->role_id == 1 || auth()->user()->role_id == 4 ? '7' : '6' }}" class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><strong>Kriteria:</strong> {{ $item->nama_kriteria }}</div>
                        <div><strong>Tahun:</strong> {{ $item->tahun }}</div>
                        <div class="col-span-2"><strong>Indikator:</strong> {{ $item->nama_indikator }}</div>
                        <div class="col-span-2">
                            <strong>Target Capaian:</strong>
                            @php
                                $targets = json_decode($item->target_capaian, true);
                            @endphp
                            @if(is_array($targets))
                                <ul class="list-disc list-inside mt-1 ml-4">
                                    @foreach($targets as $t)
                                        <li>{{ $t }}</li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="mt-1">{{ $item->target_capaian }}</p>
                            @endif
                        </div>
                        <div><strong>Tanggal Mulai:</strong> {{ date('d/m/Y', strtotime($item->tanggal_mulai)) }}</div>
                        <div><strong>Tanggal Selesai:</strong> {{ $item->tanggal_selesai ? date('d/m/Y', strtotime($item->tanggal_selesai)) : '-' }}</div>
                        @if($item->dokumen_link)
                        <div class="col-span-2">
                            <strong>Dokumen:</strong>
                            @php
                                $dokumen = json_decode($item->dokumen_link, true);
                            @endphp
                            @if(is_array($dokumen))
                                <ul class="list-disc list-inside mt-1 ml-4">
                                    @foreach($dokumen as $dok)
                                        <li><a href="{{ $dok['url'] }}" target="_blank" class="text-blue-600 hover:underline">{{ $dok['judul'] }}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                <a href="{{ $item->dokumen_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $item->dokumen_link }}</a>
                            @endif
                        </div>
                        @endif
                        @if($item->keterangan)
                        <div class="col-span-2"><strong>Keterangan:</strong> {{ $item->keterangan }}</div>
                        @endif
                        <div><strong>Status:</strong> {{ $item->status }}</div>
                        <div><strong>Dibuat Oleh:</strong> {{ $item->pembuat }}</div>
                        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 4)
                        <div><strong>Unit:</strong> {{ $item->nama_unit }}</div>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->role_id == 1 || auth()->user()->role_id == 4 ? '7' : '6' }}" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
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

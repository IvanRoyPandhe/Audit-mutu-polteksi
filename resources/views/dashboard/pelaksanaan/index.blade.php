@extends('layouts.app')

@section('title', 'Pelaksanaan')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    @if(auth()->user()->role_id == 3)
    <a href="/dashboard/pelaksanaan/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto text-center">+ Tambah Pelaksanaan</a>
    @elseif(auth()->user()->role_id == 2)
    <div class="text-sm text-gray-600">Menampilkan pelaksanaan yang sudah selesai</div>
    @else
    <div></div>
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

<div class="bg-white rounded-lg shadow overflow-x-auto">
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
            <tr id="pelaksanaan-{{ $item->pelaksanaan_id }}" class="hidden bg-gradient-to-r from-green-50 to-gray-50">
                <td colspan="{{ auth()->user()->role_id == 1 || auth()->user()->role_id == 4 ? '7' : '6' }}" class="px-6 py-4">
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
                            <div class="col-span-2 bg-blue-50 p-3 rounded-lg">
                                <strong class="text-gray-700 flex items-center"><svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>Target Capaian:</strong>
                                @php
                                    $targets = json_decode($item->target_capaian, true);
                                @endphp
                                @if(is_array($targets))
                                    <ul class="list-none mt-2 space-y-1">
                                        @foreach($targets as $index => $t)
                                            <li class="flex items-start"><span class="text-blue-600 font-bold mr-2">{{ $index + 1 }}.</span><span>{{ $t }}</span></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="mt-1">{{ $item->target_capaian }}</p>
                                @endif
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div><strong class="text-gray-700">Tanggal Mulai:</strong><br>{{ date('d/m/Y', strtotime($item->tanggal_mulai)) }}</div>
                            </div>
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div><strong class="text-gray-700">Tanggal Selesai:</strong><br>{{ $item->tanggal_selesai ? date('d/m/Y', strtotime($item->tanggal_selesai)) : '-' }}</div>
                            </div>
                            @if($item->pic)
                            <div class="col-span-2 flex items-start bg-yellow-50 p-3 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div><strong class="text-gray-700">PIC (Penanggung Jawab):</strong><br>{{ $item->pic }}</div>
                            </div>
                            @endif
                            @if($item->dokumen_link)
                            <div class="col-span-2 bg-purple-50 p-3 rounded-lg">
                                <strong class="text-gray-700 flex items-center"><svg class="w-4 h-4 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>Dokumen:</strong>
                                @php
                                    $dokumen = json_decode($item->dokumen_link, true);
                                @endphp
                                @if(is_array($dokumen))
                                    <ul class="list-none mt-2 space-y-1">
                                        @foreach($dokumen as $index => $dok)
                                            <li class="flex items-start"><svg class="w-4 h-4 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg><a href="{{ $dok['url'] }}" target="_blank" class="text-blue-600 hover:underline">{{ $dok['judul'] }}</a></li>
                                        @endforeach
                                    </ul>
                                @else
                                    <a href="{{ $item->dokumen_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $item->dokumen_link }}</a>
                                @endif
                            </div>
                            @endif
                            @if($item->keterangan)
                            <div class="col-span-2 flex items-start">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                <div class="flex-1"><strong class="text-gray-700">Keterangan:</strong><br>{{ $item->keterangan }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div><strong class="text-gray-600">Status:</strong> 
                                    <span class="inline-block px-2 py-0.5 text-xs rounded-full
                                        @if($item->status == 'Selesai') bg-green-100 text-green-800
                                        @elseif($item->status == 'Sedang Berjalan') bg-blue-100 text-blue-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $item->status }}
                                    </span>
                                </div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div><strong class="text-gray-600">Dibuat:</strong> {{ $item->pembuat }}</div>
                            </div>
                            @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 4)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <div><strong class="text-gray-600">Unit:</strong> {{ $item->nama_unit }}</div>
                            </div>
                            @endif
                        </div>
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

@extends('layouts.app')

@section('title', 'Evaluasi & Audit')

@section('content')
@if(auth()->user()->role_id != 1)
<div class="mb-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
    <div class="flex items-center">
        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <div>
            <h3 class="text-lg font-semibold text-blue-800">Hasil Audit {{ $unitName ? 'Unit ' . $unitName : 'Unit Kerja Anda' }}</h3>
            <p class="text-blue-600 text-sm">Berikut adalah hasil audit untuk unit kerja Anda</p>
        </div>
    </div>
</div>
@endif

<div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    @if(in_array(auth()->user()->role_id, [1, 2]))
    <a href="/dashboard/evaluasi/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 w-full sm:w-auto text-center">+ Tambah Evaluasi</a>
    @else
    <div></div>
    @endif
    
    <form method="GET" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Status</option>
            <option value="Draft" {{ request('status') == 'Draft' ? 'selected' : '' }}>Draft</option>
            <option value="Disetujui" {{ request('status') == 'Disetujui' ? 'selected' : '' }}>Disetujui</option>
        </select>
        <select name="evaluasi" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Evaluasi</option>
            <option value="Sesuai" {{ request('evaluasi') == 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
            <option value="Tidak Sesuai" {{ request('evaluasi') == 'Tidak Sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
            <option value="Menyimpang" {{ request('evaluasi') == 'Menyimpang' ? 'selected' : '' }}>Menyimpang</option>
            <option value="Melampaui" {{ request('evaluasi') == 'Melampaui' ? 'selected' : '' }}>Melampaui</option>
        </select>
        <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Filter</button>
        <a href="/dashboard/evaluasi" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Reset</a>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indikator</th>
                @if(auth()->user()->role_id == 1)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                @endif
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Audit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auditor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($evaluasi as $item)
            <tr>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_kriteria, 30) }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_indikator, 40) }}</td>
                @if(auth()->user()->role_id == 1)
                <td class="px-6 py-4 text-sm">{{ $item->nama_unit }}</td>
                @endif
                <td class="px-6 py-4 text-sm">{{ $item->tahun }}</td>
                <td class="px-6 py-4 text-sm">{{ date('d/m/Y', strtotime($item->tanggal_audit)) }}</td>
                <td class="px-6 py-4 text-sm">
                    @if($item->evaluasi_kesesuaian == 'Sesuai')
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $item->evaluasi_kesesuaian }}</span>
                    @elseif($item->evaluasi_kesesuaian == 'Melampaui')
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $item->evaluasi_kesesuaian }}</span>
                    @elseif($item->evaluasi_kesesuaian == 'Tidak Sesuai')
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">{{ $item->evaluasi_kesesuaian }}</span>
                    @else
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $item->evaluasi_kesesuaian }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">{{ $item->auditor }}</td>
                <td class="px-6 py-4 text-sm">
                    <a href="/dashboard/evaluasi/{{ $item->audit_id }}" class="text-blue-600 hover:text-blue-800 mr-3">Lihat Detail</a>
                    <button onclick="toggleDetail('audit-{{ $item->audit_id }}')" class="text-green-600 hover:text-green-800 mr-3">Quick View</button>
                    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                    <a href="/dashboard/evaluasi/{{ $item->audit_id }}/edit" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                    <form method="POST" action="/dashboard/evaluasi/{{ $item->audit_id }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                    @endif
                </td>
            </tr>
            <tr id="audit-{{ $item->audit_id }}" class="hidden bg-gradient-to-r from-orange-50 to-gray-50">
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
                            <div class="flex items-start bg-blue-50 p-3 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <div><strong class="text-gray-700">Tanggal Audit:</strong><br>{{ date('d/m/Y', strtotime($item->tanggal_audit)) }}</div>
                            </div>
                            <div class="flex items-start bg-gray-50 p-3 rounded-lg">
                                <svg class="w-5 h-5 text-gray-600 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div><strong class="text-gray-700">Evaluasi:</strong><br>
                                    <span class="inline-block px-2 py-1 text-xs rounded-full mt-1
                                        @if($item->evaluasi_kesesuaian == 'Sesuai') bg-green-100 text-green-800
                                        @elseif($item->evaluasi_kesesuaian == 'Melampaui') bg-blue-100 text-blue-800
                                        @elseif($item->evaluasi_kesesuaian == 'Tidak Sesuai') bg-red-100 text-red-800
                                        @else bg-yellow-100 text-yellow-800 @endif">
                                        {{ $item->evaluasi_kesesuaian }}
                                    </span>
                                </div>
                            </div>
                            @if($item->rekomendasi_perbaikan)
                            <div class="col-span-2 bg-yellow-50 border-l-4 border-yellow-500 p-3 rounded">
                                <strong class="text-gray-700 flex items-center"><svg class="w-5 h-5 mr-2 text-yellow-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"></path></svg>Rekomendasi Perbaikan (Feedback):</strong>
                                <p class="text-gray-700 mt-1 ml-7">{{ $item->rekomendasi_perbaikan }}</p>
                            </div>
                            @endif
                            @if($item->catatan_penutupan)
                            <div class="col-span-2 bg-green-50 border-l-4 border-green-500 p-3 rounded">
                                <strong class="text-gray-700 flex items-center"><svg class="w-5 h-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>Catatan Penutupan:</strong>
                                <p class="text-gray-700 mt-1 ml-7">{{ $item->catatan_penutupan }}</p>
                            </div>
                            @endif
                        </div>
                        <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-3 gap-4 text-sm">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                <div><strong class="text-gray-600">Auditor:</strong> {{ $item->auditor }}</div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                <div><strong class="text-gray-600">Unit:</strong> {{ $item->nama_unit }}</div>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <div><strong class="text-gray-600">Status:</strong> {{ $item->status }}</div>
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

<script>
function toggleDetail(id) {
    const element = document.getElementById(id);
    element.classList.toggle('hidden');
}
</script>
@endsection

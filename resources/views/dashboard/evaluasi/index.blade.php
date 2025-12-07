@extends('layouts.app')

@section('title', 'Evaluasi & Audit')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <a href="/dashboard/evaluasi/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Evaluasi</a>
    
    <form method="GET" class="flex gap-2">
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

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indikator</th>
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
                    <button onclick="toggleDetail('audit-{{ $item->audit_id }}')" class="text-blue-600 hover:text-blue-800 mr-3">Detail</button>
                    <a href="/dashboard/evaluasi/{{ $item->audit_id }}/edit" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                    <form method="POST" action="/dashboard/evaluasi/{{ $item->audit_id }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            <tr id="audit-{{ $item->audit_id }}" class="hidden bg-gray-50">
                <td colspan="7" class="px-6 py-4">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div><strong>Kriteria:</strong> {{ $item->nama_kriteria }}</div>
                        <div><strong>Tahun:</strong> {{ $item->tahun }}</div>
                        <div class="col-span-2"><strong>Indikator:</strong> {{ $item->nama_indikator }}</div>
                        <div><strong>Tanggal Audit:</strong> {{ date('d/m/Y', strtotime($item->tanggal_audit)) }}</div>
                        <div><strong>Evaluasi:</strong> {{ $item->evaluasi_kesesuaian }}</div>
                        @if($item->rekomendasi_perbaikan)
                        <div class="col-span-2"><strong>Rekomendasi Perbaikan (Feedback):</strong> {{ $item->rekomendasi_perbaikan }}</div>
                        @endif
                        @if($item->catatan_penutupan)
                        <div class="col-span-2"><strong>Catatan Penutupan:</strong> {{ $item->catatan_penutupan }}</div>
                        @endif
                        <div><strong>Auditor:</strong> {{ $item->auditor }}</div>
                        <div><strong>Unit:</strong> {{ $item->nama_unit }}</div>
                        <div><strong>Status:</strong> {{ $item->status }}</div>
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

<script>
function toggleDetail(id) {
    const element = document.getElementById(id);
    element.classList.toggle('hidden');
}
</script>
@endsection

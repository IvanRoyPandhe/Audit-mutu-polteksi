@extends('layouts.app')

@section('title', 'Laporan Audit')

@section('content')
<div class="mb-6 flex flex-col gap-4">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <h2 class="text-xl font-semibold">Laporan Audit</h2>
        
        <form method="GET" action="/dashboard/laporan/pdf" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <input type="hidden" name="unit_id" value="{{ request('unit_id') }}">
            <input type="hidden" name="tahun" value="{{ request('tahun') }}">
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/></svg>
                Download PDF
            </button>
        </form>
    </div>
    
    <form method="GET" action="/dashboard/laporan" class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
        <select name="unit_id" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Unit</option>
            @foreach($units as $unit)
            <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
            @endforeach
        </select>
        <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg">
            <option value="">Semua Tahun</option>
            @foreach($tahun_list as $t)
            <option value="{{ $t }}" {{ request('tahun') == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Filter</button>
        <a href="/dashboard/laporan" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-400">Reset</a>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indikator</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Audit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Evaluasi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hasil Audit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auditor</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($laporan as $item)
            <tr>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_kriteria, 30) }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->nama_indikator, 40) }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->nama_unit }}</td>
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
                <td class="px-6 py-4 text-sm">
                    @if($item->hasil_audit == 'Tidak Ada')
                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">{{ $item->hasil_audit }}</span>
                    @elseif($item->hasil_audit == 'Minor')
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $item->hasil_audit }}</span>
                    @elseif($item->hasil_audit == 'Mayor')
                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">{{ $item->hasil_audit }}</span>
                    @elseif($item->hasil_audit == 'Observasi')
                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">{{ $item->hasil_audit }}</span>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm">{{ $item->auditor }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

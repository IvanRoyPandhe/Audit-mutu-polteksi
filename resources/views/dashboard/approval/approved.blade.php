@extends('layouts.app')

@section('title', 'Data Sudah Disetujui')

@section('content')
<div class="mb-6 flex gap-2">
    <a href="/dashboard/approval" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Menunggu Approval</a>
    <a href="/dashboard/approval/approved" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Sudah Disetujui</a>
</div>

<div class="mb-6 flex gap-2">
    <a href="/dashboard/approval/approved?type=standar" class="px-4 py-2 rounded-lg {{ request('type', 'standar') == 'standar' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700' }}">Standar Mutu</a>
    <a href="/dashboard/approval/approved?type=kriteria" class="px-4 py-2 rounded-lg {{ request('type') == 'kriteria' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700' }}">Kriteria</a>
    <a href="/dashboard/approval/approved?type=indikator" class="px-4 py-2 rounded-lg {{ request('type') == 'indikator' ? 'bg-purple-600 text-white' : 'bg-gray-200 text-gray-700' }}">Indikator Kinerja</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    @if(request('type', 'standar') == 'standar')
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Standar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Disetujui</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($data as $item)
            <tr>
                <td class="px-6 py-4 text-sm">{{ $item->kode_standar }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->nama_standar }}</td>
                <td class="px-6 py-4 text-sm">{{ date('d/m/Y', strtotime($item->tanggal_disetujui)) }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->penyetuju }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @elseif(request('type') == 'kriteria')
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Standar Mutu</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Disetujui</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($data as $item)
            <tr>
                <td class="px-6 py-4 text-sm">{{ $item->kode_kriteria }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->nama_kriteria }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->nama_standar }}</td>
                <td class="px-6 py-4 text-sm">{{ date('d/m/Y', strtotime($item->tanggal_disetujui)) }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->penyetuju }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @else
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Indikator</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal Disetujui</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Disetujui Oleh</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($data as $item)
            <tr>
                <td class="px-6 py-4 text-sm">{{ $item->kode_indikator }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->nama_indikator }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->nama_kriteria }}</td>
                <td class="px-6 py-4 text-sm">{{ date('d/m/Y', strtotime($item->tanggal_disetujui)) }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->penyetuju }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @endif
</div>
@endsection

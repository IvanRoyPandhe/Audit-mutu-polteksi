@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Review Hasil Audit - Direktur</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Review</label>
                <select name="status_review" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua</option>
                    <option value="pending" {{ request('status_review') == 'pending' ? 'selected' : '' }}>Belum Direview</option>
                    <option value="reviewed" {{ request('status_review') == 'reviewed' ? 'selected' : '' }}>Sudah Direview</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                <select name="unit_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                            {{ $unit->nama_unit }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua Tahun</option>
                    @foreach($tahun_list as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Standar Mutu</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Indikator</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tahun</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hasil Audit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status Review</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($audits as $audit)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $audit->nama_standar }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $audit->nama_indikator }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $audit->nama_unit }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $audit->tahun }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($audit->hasil_audit == 'Mayor') bg-red-100 text-red-800
                                @elseif($audit->hasil_audit == 'Minor') bg-yellow-100 text-yellow-800
                                @else bg-green-100 text-green-800
                                @endif">
                                {{ $audit->hasil_audit }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($audit->catatan_peningkatan)
                                <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">
                                    ✓ Sudah Direview
                                </span>
                            @else
                                <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">
                                    Belum Direview
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="/dashboard/direktur-review/{{ $audit->audit_id }}" 
                               class="text-blue-600 hover:text-blue-800">
                                {{ $audit->catatan_peningkatan ? 'Lihat Review' : 'Review' }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data audit yang perlu direview
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $audits->links() }}
    </div>
</div>
@endsection

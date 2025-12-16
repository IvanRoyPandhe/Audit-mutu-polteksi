@extends('layouts.app')

@section('title', 'Penugasan Auditor per Unit')

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Tugaskan Auditor ke Unit Kerja</h2>
    <form method="POST" action="/dashboard/unit-auditors" class="flex gap-4 items-end">
        @csrf
        <div class="flex-1">
            <label class="block text-sm font-medium mb-2">Unit Kerja</label>
            <select name="unit_id" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="">Pilih Unit</option>
                @foreach($units as $unit)
                <option value="{{ $unit->unit_id }}">{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium mb-2">Auditor</label>
            <select name="auditor_id" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="">Pilih Auditor</option>
                @foreach($auditors as $auditor)
                <option value="{{ $auditor->user_id }}">{{ $auditor->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tugaskan</button>
    </form>
</div>

@forelse($assignments as $unitName => $auditorList)
<div class="bg-white rounded-lg shadow mb-4">
    <div class="px-6 py-4 border-b bg-blue-50">
        <h3 class="text-lg font-semibold">{{ $unitName }}</h3>
    </div>
    <div class="p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($auditorList as $assignment)
            <div class="flex items-center justify-between bg-green-50 px-3 py-2 rounded-lg">
                <div>
                    <div class="font-medium text-green-800">{{ $assignment->auditor_name }}</div>
                    <div class="text-xs text-green-600">Ditugaskan: {{ date('d/m/Y', strtotime($assignment->assigned_at)) }}</div>
                </div>
                <form method="POST" action="/dashboard/unit-auditors/{{ $assignment->id }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('Hapus penugasan?')">×</button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@empty
<div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
    Belum ada penugasan auditor
</div>
@endforelse
@endsection
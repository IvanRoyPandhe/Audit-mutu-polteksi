@extends('layouts.app')

@section('title', 'Penugasan Auditor')

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">
    <h2 class="text-xl font-semibold mb-4">Tambah Penugasan Auditor</h2>
    <form method="POST" action="/dashboard/auditor-assignments" class="flex gap-4 items-end">
        @csrf
        <div class="flex-1">
            <label class="block text-sm font-medium mb-2">Auditor</label>
            <select name="auditor_id" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="">Pilih Auditor</option>
                @foreach($auditors as $auditor)
                <option value="{{ $auditor->user_id }}">{{ $auditor->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="flex-1">
            <label class="block text-sm font-medium mb-2">Unit Kerja</label>
            <select name="unit_id" class="w-full px-3 py-2 border rounded-lg" required>
                <option value="">Pilih Unit</option>
                @foreach($units as $unit)
                <option value="{{ $unit->unit_id }}">{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Tugaskan</button>
    </form>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h2 class="text-xl font-semibold">Daftar Penugasan Auditor</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auditor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Kerja</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ditugaskan Oleh</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($assignments as $assignment)
                <tr>
                    <td class="px-6 py-4">{{ $assignment->auditor_name }}</td>
                    <td class="px-6 py-4">{{ $assignment->nama_unit }}</td>
                    <td class="px-6 py-4">{{ $assignment->assigned_by_name }}</td>
                    <td class="px-6 py-4">{{ date('d/m/Y', strtotime($assignment->assigned_at)) }}</td>
                    <td class="px-6 py-4">
                        <form method="POST" action="/dashboard/auditor-assignments/{{ $assignment->id }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Hapus penugasan ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
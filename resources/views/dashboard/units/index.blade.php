@extends('layouts.app')

@section('title', 'Management Unit Kerja')

@section('content')
<div class="mb-6">
    <a href="/dashboard/units/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Unit</a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipe Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pimpinan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($units as $unit)
            <tr>
                <td class="px-6 py-4 text-sm">{{ $unit->nama_unit }}</td>
                <td class="px-6 py-4 text-sm">{{ $unit->tipe_unit }}</td>
                <td class="px-6 py-4 text-sm">{{ $unit->pimpinan }}</td>
                <td class="px-6 py-4 text-sm">
                    <a href="/dashboard/units/{{ $unit->unit_id }}/edit" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                    <form method="POST" action="/dashboard/units/{{ $unit->unit_id }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

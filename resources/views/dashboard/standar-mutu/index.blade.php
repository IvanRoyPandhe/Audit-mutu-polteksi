@extends('layouts.app')

@section('title', 'Standar Mutu')

@section('content')
@if(auth()->user()->role_id == 1)
<div class="mb-6">
    <a href="/dashboard/standar-mutu/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Standar Mutu</a>
</div>
@endif

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Standar</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deskripsi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pembuat</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($standar as $item)
            <tr>
                <td class="px-6 py-4 text-sm font-medium">{{ $item->kode_standar }}</td>
                <td class="px-6 py-4 text-sm">{{ $item->nama_standar }}</td>
                <td class="px-6 py-4 text-sm">{{ Str::limit($item->deskripsi, 50) }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">{{ $item->status }}</span>
                </td>
                <td class="px-6 py-4 text-sm">{{ $item->pembuat }}</td>
                <td class="px-6 py-4 text-sm">
                    <a href="/dashboard/standar-mutu/{{ $item->standar_id }}/edit" class="text-blue-600 hover:text-blue-800 mr-3">Edit</a>
                    <form method="POST" action="/dashboard/standar-mutu/{{ $item->standar_id }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

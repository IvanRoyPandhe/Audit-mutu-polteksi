@extends('layouts.app')

@section('title', 'Kebijakan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-xl font-semibold">Daftar Kebijakan</h2>
    @if(auth()->user()->role_id == 1)
    <a href="/dashboard/buku-kebijakan/create/kebijakan" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Kebijakan</a>
    @endif
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Link</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                @if(auth()->user()->role_id == 1)
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($data as $index => $item)
            <tr>
                <td class="px-6 py-4 text-sm">{{ $index + 1 }}</td>
                <td class="px-6 py-4 text-sm font-medium">{{ $item->judul }}</td>
                <td class="px-6 py-4 text-sm">
                    <a href="{{ $item->link }}" target="_blank" class="text-blue-600 hover:text-blue-800 underline">Lihat Dokumen</a>
                </td>
                <td class="px-6 py-4 text-sm">{{ date('d/m/Y', strtotime($item->created_at)) }}</td>
                @if(auth()->user()->role_id == 1)
                <td class="px-6 py-4 text-sm">
                    <a href="/dashboard/buku-kebijakan/{{ $item->id }}/edit" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                    <form method="POST" action="/dashboard/buku-kebijakan/{{ $item->id }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Yakin hapus?')">Hapus</button>
                    </form>
                </td>
                @endif
            </tr>
            @empty
            <tr>
                <td colspan="{{ auth()->user()->role_id == 1 ? '5' : '4' }}" class="px-6 py-4 text-center text-gray-500">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
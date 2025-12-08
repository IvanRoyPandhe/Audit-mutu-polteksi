@extends('layouts.app')

@section('title', 'Management Users')

@section('content')
<div class="mb-6">
    <a href="/dashboard/users/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah User</a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
            <tr>
                <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                <td class="px-6 py-4 text-sm">{{ $user->role_name }}</td>
                <td class="px-6 py-4 text-sm">{{ $user->nama_unit }}</td>
                <td class="px-6 py-4 text-sm">
                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">{{ $user->status }}</span>
                </td>
                <td class="px-6 py-4 text-sm">
                    <a href="/dashboard/users/{{ $user->user_id }}/edit" class="text-yellow-600 hover:text-yellow-800 mr-3">Edit</a>
                    <form method="POST" action="/dashboard/users/{{ $user->user_id }}" class="inline">
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

@extends('layouts.app')

@section('title', 'Management Roles')

@section('content')
<div class="mb-6">
    <a href="/dashboard/roles/create" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">+ Tambah Role</a>
</div>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($roles as $role)
            <tr>
                <td class="px-6 py-4 text-sm font-medium">{{ $role->role_name }}</td>
                <td class="px-6 py-4 text-sm">{{ $role->description }}</td>
                <td class="px-6 py-4 text-sm">
                    <form method="POST" action="/dashboard/roles/{{ $role->role_id }}" class="inline">
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

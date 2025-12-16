@extends('layouts.app')

@section('title', 'Edit Role Permissions')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-semibold mb-4">Edit Permissions untuk {{ $role->role_name }}</h2>
    
    <form method="POST" action="/dashboard/roles/{{ $role->role_id }}">
        @csrf
        @method('PUT')
        
        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-3">Pilih Halaman yang Dapat Diakses:</label>
            @php
                $currentPermissions = json_decode($role->permissions ?? '[]', true) ?: [];
            @endphp
            
            <div class="grid grid-cols-2 gap-3">
                @foreach($availablePermissions as $key => $label)
                <label class="flex items-center">
                    <input type="checkbox" name="permissions[]" value="{{ $key }}" 
                           class="rounded border-gray-300 text-blue-600 mr-2"
                           {{ in_array($key, $currentPermissions) ? 'checked' : '' }}>
                    <span class="text-sm text-gray-700">{{ $label }}</span>
                </label>
                @endforeach
            </div>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update Permissions</button>
            <a href="/dashboard/roles" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection
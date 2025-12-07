@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/users/{{ $user->user_id }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Nama</label>
            <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Email</label>
            <input type="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
            <input type="password" name="password" class="w-full px-4 py-2 border border-gray-300 rounded-lg">
            @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Role</label>
            <select name="role_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                @foreach($roles as $role)
                <option value="{{ $role->role_id }}" {{ $user->role_id == $role->role_id ? 'selected' : '' }}>{{ $role->role_name }}</option>
                @endforeach
            </select>
            @error('role_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Unit</label>
            <select name="unit_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                @foreach($units as $unit)
                <option value="{{ $unit->unit_id }}" {{ $user->unit_id == $unit->unit_id ? 'selected' : '' }}>{{ $unit->nama_unit }}</option>
                @endforeach
            </select>
            @error('unit_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
            <a href="/dashboard/users" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

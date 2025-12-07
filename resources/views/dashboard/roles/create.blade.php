@extends('layouts.app')

@section('title', 'Tambah Role')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/roles">
        @csrf
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Role Name</label>
            <input type="text" name="role_name" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg"></textarea>
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Simpan</button>
            <a href="/dashboard/roles" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

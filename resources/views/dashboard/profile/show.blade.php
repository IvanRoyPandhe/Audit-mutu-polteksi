@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-8 mb-6 text-white">
        <div class="flex items-center">
            <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center text-4xl font-bold text-blue-600">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div class="ml-6">
                <h1 class="text-3xl font-bold">{{ $user->name }}</h1>
                <p class="text-blue-100 mt-1">{{ $user->email }}</p>
                <div class="flex gap-2 mt-3">
                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm">{{ $user->role_name }}</span>
                    <span class="bg-white/20 px-3 py-1 rounded-full text-sm">{{ $user->nama_unit }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Informasi Personal</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-500">Nama Lengkap</label>
                    <p class="text-gray-800 font-medium">{{ $user->name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Email</label>
                    <p class="text-gray-800 font-medium">{{ $user->email }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Status</label>
                    <p><span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">{{ $user->status }}</span></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Informasi Organisasi</h2>
            <div class="space-y-4">
                <div>
                    <label class="text-sm text-gray-500">Role</label>
                    <p class="text-gray-800 font-medium">{{ $user->role_name }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">Unit Kerja</label>
                    <p class="text-gray-800 font-medium">{{ $user->nama_unit }}</p>
                </div>
                <div>
                    <label class="text-sm text-gray-500">User ID</label>
                    <p class="text-gray-800 font-medium">#{{ $user->user_id }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 flex gap-3">
        <a href="/dashboard/profile/edit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 flex items-center gap-2">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/></svg>
            Edit Profile
        </a>
        <a href="/dashboard" class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-400">Kembali</a>
    </div>
</div>
@endsection

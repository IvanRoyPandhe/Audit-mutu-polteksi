@extends('layouts.app')

@section('title', 'Edit Evaluasi')

@section('content')
<div class="max-w-2xl bg-white rounded-lg shadow p-6">
    <form method="POST" action="/dashboard/evaluasi/{{ $audit->audit_id }}">
        @csrf
        @method('PUT')
        
        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Kriteria</label>
            <input type="text" value="{{ $audit->nama_kriteria }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Indikator</label>
            <input type="text" value="{{ $audit->nama_indikator }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tahun</label>
            <input type="text" value="{{ $audit->tahun }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100" readonly>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Auditor</label>
            <select name="auditor_id" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                @foreach($auditor as $item)
                <option value="{{ $item->user_id }}" {{ $audit->auditor_id == $item->user_id ? 'selected' : '' }}>{{ $item->name }}</option>
                @endforeach
            </select>
            @error('auditor_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Tanggal Audit</label>
            <input type="date" name="tanggal_audit" value="{{ $audit->tanggal_audit }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
            @error('tanggal_audit')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Evaluasi Kesesuaian</label>
            <select name="evaluasi_kesesuaian" class="w-full px-4 py-2 border border-gray-300 rounded-lg" required>
                <option value="Sesuai" {{ $audit->evaluasi_kesesuaian == 'Sesuai' ? 'selected' : '' }}>Sesuai</option>
                <option value="Tidak Sesuai" {{ $audit->evaluasi_kesesuaian == 'Tidak Sesuai' ? 'selected' : '' }}>Tidak Sesuai</option>
                <option value="Menyimpang" {{ $audit->evaluasi_kesesuaian == 'Menyimpang' ? 'selected' : '' }}>Menyimpang</option>
                <option value="Melampaui" {{ $audit->evaluasi_kesesuaian == 'Melampaui' ? 'selected' : '' }}>Melampaui</option>
            </select>
            @error('evaluasi_kesesuaian')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 text-sm font-medium mb-2">Rekomendasi Perbaikan (Feedback/Tindak Lanjut)</label>
            <textarea name="rekomendasi_perbaikan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ $audit->rekomendasi_perbaikan }}</textarea>
            @error('rekomendasi_perbaikan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 text-sm font-medium mb-2">Catatan Penutupan</label>
            <textarea name="catatan_penutupan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg">{{ $audit->catatan_penutupan }}</textarea>
            @error('catatan_penutupan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-2">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">Update</button>
            <a href="/dashboard/evaluasi" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">Batal</a>
        </div>
    </form>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Approval')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row gap-2">
    <a href="/dashboard/approval" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Master Data</a>
    <a href="/dashboard/approval/penetapan" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">Penetapan</a>
    <a href="/dashboard/approval/approved" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">Sudah Disetujui</a>
</div>

<div class="space-y-6">
    @if($standar->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-blue-600">Standar Mutu - Menunggu Persetujuan</h3>
        <div class="space-y-4">
            @foreach($standar as $item)
            <div class="border rounded-lg p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="font-semibold text-lg">{{ $item->kode_standar }} - {{ $item->nama_standar }}</h4>
                        <p class="text-sm text-gray-600 mt-2">{{ $item->deskripsi }}</p>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button onclick="showApproveModal('standar', {{ $item->standar_id }})" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Setujui</button>
                        <button onclick="showRejectModal('standar', {{ $item->standar_id }})" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Tolak</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($kriteria->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-purple-600">Kriteria - Menunggu Persetujuan</h3>
        <div class="space-y-4">
            @foreach($kriteria as $item)
            <div class="border rounded-lg p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="font-semibold text-lg">{{ $item->kode_kriteria }} - {{ $item->nama_kriteria }}</h4>
                        <p class="text-sm text-gray-600 mt-2">{{ $item->deskripsi }}</p>
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button onclick="showApproveModal('kriteria', {{ $item->kriteria_id }})" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Setujui</button>
                        <button onclick="showRejectModal('kriteria', {{ $item->kriteria_id }})" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Tolak</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($indikator->count() > 0)
    <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold mb-4 text-orange-600">Indikator Kinerja - Menunggu Persetujuan</h3>
        <div class="space-y-4">
            @foreach($indikator as $item)
            <div class="border rounded-lg p-4 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h4 class="font-semibold text-lg">{{ $item->kode_indikator }} - {{ $item->nama_indikator }}</h4>
                        @php
                            $targets = json_decode($item->target, true);
                        @endphp
                        @if(is_array($targets))
                        <div class="mt-2">
                            <strong class="text-sm">Target:</strong>
                            <ul class="list-disc list-inside text-sm text-gray-600 ml-4">
                                @foreach($targets as $t)
                                <li>{{ $t }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                    <div class="flex gap-2 ml-4">
                        <button onclick="showApproveModal('indikator', {{ $item->indikator_id }})" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Setujui</button>
                        <button onclick="showRejectModal('indikator', {{ $item->indikator_id }})" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Tolak</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($standar->count() == 0 && $kriteria->count() == 0 && $indikator->count() == 0)
    <div class="bg-white rounded-lg shadow p-6 text-center text-gray-500">
        Tidak ada data yang menunggu persetujuan
    </div>
    @endif
</div>

<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">Alasan Penolakan</h3>
        <form method="POST" action="/dashboard/approval/reject">
            @csrf
            <input type="hidden" name="type" id="rejectType">
            <input type="hidden" name="id" id="rejectId">
            <textarea name="alasan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg" placeholder="Masukkan alasan penolakan..." required></textarea>
            <div class="flex gap-2 mt-4">
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Tolak</button>
                <button type="button" onclick="closeRejectModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
            </div>
        </form>
    </div>
</div>

<div id="approveModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-full">
        <h3 class="text-lg font-semibold mb-4">Konfirmasi Persetujuan</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyetujui data ini?</p>
        <form method="POST" action="/dashboard/approval" id="approveForm">
            @csrf
            <input type="hidden" name="type" id="approveType">
            <input type="hidden" name="id" id="approveId">
            <div class="flex gap-2">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ya, Setujui</button>
                <button type="button" onclick="closeApproveModal()" class="bg-gray-300 text-gray-700 px-4 py-2 rounded hover:bg-gray-400">Batal</button>
            </div>
        </form>
    </div>
</div>

<script>
function showApproveModal(type, id) {
    document.getElementById('approveType').value = type;
    document.getElementById('approveId').value = id;
    document.getElementById('approveModal').classList.remove('hidden');
}

function closeApproveModal() {
    document.getElementById('approveModal').classList.add('hidden');
}

function showRejectModal(type, id) {
    document.getElementById('rejectType').value = type;
    document.getElementById('rejectId').value = id;
    document.getElementById('rejectModal').classList.remove('hidden');
}

function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-6">
        <a href="/dashboard/direktur-review" class="text-blue-600 hover:text-blue-800">
            ← Kembali ke Daftar Review
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Detail Hasil Audit</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Standar Mutu</label>
                <p class="text-gray-900">{{ $audit->nama_standar }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Kriteria</label>
                <p class="text-gray-900">{{ $audit->nama_kriteria }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Indikator Kinerja</label>
                <p class="text-gray-900">{{ $audit->nama_indikator }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Unit</label>
                <p class="text-gray-900">{{ $audit->nama_unit }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <p class="text-gray-900">{{ $audit->tahun }}</p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Target Capaian</label>
                <p class="text-gray-900">{{ $audit->target_capaian }}%</p>
            </div>
        </div>

        <div class="border-t pt-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Hasil Evaluasi</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capaian Realisasi</label>
                    <p class="text-gray-900">{{ $audit->capaian_realisasi }}%</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Hasil Audit</label>
                    <span class="px-3 py-1 text-sm rounded-full
                        @if($audit->hasil_audit == 'Mayor') bg-red-100 text-red-800
                        @elseif($audit->hasil_audit == 'Minor') bg-yellow-100 text-yellow-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ $audit->hasil_audit }}
                    </span>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Evaluasi Kesesuaian</label>
                    <p class="text-gray-900">{{ $audit->evaluasi_kesesuaian }}</p>
                </div>
                @if($audit->temuan)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Temuan</label>
                    <p class="text-gray-900">{{ $audit->temuan }}</p>
                </div>
                @endif
                @if($audit->rekomendasi)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rekomendasi</label>
                    <p class="text-gray-900">{{ $audit->rekomendasi }}</p>
                </div>
                @endif
            </div>
        </div>

        <div class="border-t pt-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Catatan Peningkatan (Review Direktur)</h2>
            
            @if($audit->catatan_peningkatan)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                    <p class="text-gray-900 whitespace-pre-line">{{ $audit->catatan_peningkatan }}</p>
                    <p class="text-sm text-gray-600 mt-2">
                        Direview pada: {{ \Carbon\Carbon::parse($audit->tanggal_review_direktur)->format('d M Y H:i') }}
                    </p>
                </div>
                
                <form method="POST" action="/dashboard/direktur-review/{{ $audit->audit_id }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Update Catatan Peningkatan
                        </label>
                        <textarea name="catatan_peningkatan" rows="6" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Masukkan catatan peningkatan untuk standar yang sudah tercapai...">{{ $audit->catatan_peningkatan }}</textarea>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Update Catatan
                    </button>
                </form>
            @else
                <form method="POST" action="/dashboard/direktur-review/{{ $audit->audit_id }}">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan Peningkatan <span class="text-red-500">*</span>
                        </label>
                        <textarea name="catatan_peningkatan" rows="6" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                  placeholder="Masukkan catatan peningkatan untuk standar yang sudah tercapai..."></textarea>
                        <p class="text-sm text-gray-600 mt-1">
                            Berikan catatan atau arahan untuk peningkatan berkelanjutan terhadap standar yang sudah tercapai.
                        </p>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700">
                        Simpan Catatan
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection

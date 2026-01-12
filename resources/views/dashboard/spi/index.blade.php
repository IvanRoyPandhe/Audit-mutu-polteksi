@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">SPI Monitoring Dashboard</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Pelaksanaan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Selesai</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['selesai'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Sedang Berjalan</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['berjalan'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Terlambat</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['terlambat'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua Status</option>
                    <option value="terlambat" {{ request('status_filter') == 'terlambat' ? 'selected' : '' }}>Terlambat</option>
                    <option value="berjalan" {{ request('status_filter') == 'berjalan' ? 'selected' : '' }}>Sedang Berjalan</option>
                    <option value="selesai" {{ request('status_filter') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Unit</label>
                <select name="unit_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua Unit</option>
                    @foreach($units as $unit)
                        <option value="{{ $unit->unit_id }}" {{ request('unit_id') == $unit->unit_id ? 'selected' : '' }}>
                            {{ $unit->nama_unit }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="">Semua Tahun</option>
                    @foreach($tahun_list as $tahun)
                        <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Standar/Indikator</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">PIC</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Auditor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deadline</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pelaksanaan as $item)
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $item->nama_standar }}</div>
                            <div class="text-sm text-gray-500">{{ $item->nama_indikator }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->nama_unit }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->pic_name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $item->auditor_name ?? 'Belum ditugaskan' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full
                                @if($item->status_monitoring == 'Selesai') bg-green-100 text-green-800
                                @elseif($item->status_monitoring == 'Terlambat') bg-red-100 text-red-800
                                @elseif($item->status_monitoring == 'Sedang Berjalan') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ $item->status_monitoring }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <button onclick="openNotificationModal({{ $item->pelaksanaan_id }})" 
                                    class="text-blue-600 hover:text-blue-800">
                                Kirim Peringatan
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada data pelaksanaan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pelaksanaan->links() }}
    </div>
</div>

<!-- Notification Modal -->
<div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Kirim Notifikasi</h3>
                
                <form id="notificationForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Penerima</label>
                        <div id="usersList" class="space-y-2 max-h-32 overflow-y-auto border rounded p-2">
                            <!-- Users will be loaded here -->
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Notifikasi</label>
                        <select name="type" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="reminder">Pengingat</option>
                            <option value="warning">Peringatan</option>
                            <option value="info">Informasi</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul</label>
                        <input type="text" name="title" class="w-full px-3 py-2 border border-gray-300 rounded-lg" 
                               placeholder="Judul notifikasi" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pesan</label>
                        <textarea name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg" 
                                  placeholder="Tulis pesan notifikasi..." required></textarea>
                    </div>

                    <input type="hidden" name="pelaksanaan_id" id="pelaksanaanId">

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeNotificationModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Kirim Notifikasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
let currentPelaksanaanId = null;

function openNotificationModal(pelaksanaanId) {
    currentPelaksanaanId = pelaksanaanId;
    document.getElementById('pelaksanaanId').value = pelaksanaanId;
    document.getElementById('notificationModal').classList.remove('hidden');
    
    // Load users for this pelaksanaan
    loadUsers(pelaksanaanId);
}

function closeNotificationModal() {
    document.getElementById('notificationModal').classList.add('hidden');
    document.getElementById('notificationForm').reset();
}

function loadUsers(pelaksanaanId) {
    fetch(`/dashboard/spi/users?pelaksanaan_id=${pelaksanaanId}`)
        .then(response => response.json())
        .then(users => {
            const usersList = document.getElementById('usersList');
            usersList.innerHTML = '';
            
            users.forEach(user => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <label class="flex items-center">
                        <input type="checkbox" name="user_ids[]" value="${user.user_id}" class="mr-2">
                        <span class="text-sm">${user.name} (${user.role})</span>
                    </label>
                `;
                usersList.appendChild(div);
            });
        });
}

document.getElementById('notificationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const userIds = Array.from(document.querySelectorAll('input[name="user_ids[]"]:checked')).map(cb => cb.value);
    
    if (userIds.length === 0) {
        alert('Pilih minimal satu penerima');
        return;
    }

    const data = {
        user_ids: userIds,
        title: formData.get('title'),
        message: formData.get('message'),
        type: formData.get('type'),
        pelaksanaan_id: formData.get('pelaksanaan_id'),
        _token: '{{ csrf_token() }}'
    };

    fetch('/dashboard/spi/send-notification', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert(result.message);
            closeNotificationModal();
        } else {
            alert('Gagal mengirim notifikasi');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi kesalahan');
    });
});
</script>
@endsection
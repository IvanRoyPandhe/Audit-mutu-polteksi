@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Notifikasi</h1>
        <button onclick="markAllAsRead()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Tandai Semua Dibaca
        </button>
    </div>

    <div class="bg-white rounded-lg shadow-md">
        @forelse($notifications as $notification)
            <div class="border-b border-gray-200 p-6 {{ !$notification->is_read ? 'bg-blue-50' : '' }}">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <span class="px-2 py-1 text-xs rounded-full mr-2
                                @if($notification->type == 'warning') bg-red-100 text-red-800
                                @elseif($notification->type == 'reminder') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif">
                                @if($notification->type == 'warning') Peringatan
                                @elseif($notification->type == 'reminder') Pengingat
                                @else Informasi
                                @endif
                            </span>
                            @if(!$notification->is_read)
                                <span class="w-2 h-2 bg-blue-600 rounded-full"></span>
                            @endif
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $notification->title }}</h3>
                        <p class="text-gray-700 mb-2">{{ $notification->message }}</p>
                        <div class="text-sm text-gray-500">
                            Dari: {{ $notification->sender->name }} • 
                            {{ $notification->created_at->diffForHumans() }}
                        </div>
                    </div>
                    @if(!$notification->is_read)
                        <button onclick="markAsRead({{ $notification->id }})" 
                                class="text-blue-600 hover:text-blue-800 text-sm">
                            Tandai Dibaca
                        </button>
                    @endif
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-gray-500">
                Tidak ada notifikasi
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
</div>

<script>
function markAsRead(id) {
    fetch(`/dashboard/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        }
    });
}

function markAllAsRead() {
    fetch('/dashboard/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            location.reload();
        }
    });
}
</script>
@endsection
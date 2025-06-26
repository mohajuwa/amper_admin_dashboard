{{-- resources/views/admin/discount-codes/partials/status-badge.blade.php --}}
<span class="badge {{ $expiryData['statusClass'] }} px-3 py-2">
    <i class="{{ $expiryData['statusIcon'] }} mr-1"></i>{{ $expiryData['statusText'] }}
</span>

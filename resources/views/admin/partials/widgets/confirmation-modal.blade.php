{{-- Confirmation Modal Widget --}}
@props([
    'modalId' => 'confirmationModal',
    'title' => 'تأكيد العملية',
    'message' => 'هل أنت متأكد من أنك تريد المتابعة؟',
    'confirmText' => 'تأكيد',
    'cancelText' => 'إلغاء',
    'confirmClass' => 'btn-danger',
    'cancelClass' => 'btn-secondary'
])

<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $modalId }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="modal-message">{{ $message }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn {{ $cancelClass }}" data-dismiss="modal">{{ $cancelText }}</button>
                <button type="button" class="btn {{ $confirmClass }}" id="{{ $modalId }}Confirm">{{ $confirmText }}</button>
            </div>
        </div>
    </div>
</div>
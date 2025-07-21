{{-- resources/views/admin/car_make/partials/mobile-card-body.blade.php --}}
<div class="row mt-2">
    <div class="col-12">
        <div class="popularity-mobile">
            @include('admin.car_make.partials.popularity-badge', [
                'popularityData' => $popularityData,
                'mobile' => true
            ])
        </div>
    </div>
</div>
<?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class VendorResponse extends Model
        {
            use HasFactory;
            protected $primaryKey = 'response_id';
            protected $table = 'vendor_responses'; // Already plural, but add it anyway.

            protected $guarded = [];

            public function offer() {
                return $this->belongsTo(OrderOffer::class, 'offer_id', 'offer_id');
            }

            public function vendor() {
                return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
            }
        }
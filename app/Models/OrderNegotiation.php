<?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class OrderNegotiation extends Model
        {
            use HasFactory;
            protected $primaryKey = 'negotiation_id';
            protected $table = 'order_negotiations'; // Already plural, but add it anyway.

            protected $guarded = [];

            public function order() {
                return $this->belongsTo(OrderModel::class, 'order_id', 'order_id');
            }

            public function vendor() {
                return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
            }
        }
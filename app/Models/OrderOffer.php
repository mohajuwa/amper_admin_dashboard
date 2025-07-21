<?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class OrderOffer extends Model
        {
            use HasFactory;
            protected $table = 'order_offers'; // This one is already plural, but it's good practice to be explicit.

            protected $primaryKey = 'offer_id';
            protected $guarded = [];

            public function order() {
                return $this->belongsTo(OrderModel::class, 'order_id', 'order_id');
            }

            public function vendor() {
                return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
            }

            public function admin() {
                return $this->belongsTo(Admin::class, 'admin_id', 'id');
            }

            public function vendorResponses() {
                return $this->hasMany(VendorResponse::class, 'offer_id', 'offer_id');
            }
        }
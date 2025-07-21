<?php

        namespace App\Models;

        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Model;

        class CustomerResponse extends Model
        {
            use HasFactory;
            protected $primaryKey = 'response_id';
            protected $table = 'customer_responses'; // Already plural, but add it anyway.

            protected $guarded = [];

            public function order() {
                return $this->belongsTo(OrderModel::class, 'order_id', 'order_id');
            }

            public function offer() {
                return $this->belongsTo(OrderOffer::class, 'offer_id', 'offer_id');
            }

            public function user() {
                return $this->belongsTo(User::class, 'user_id', 'user_id');
            }
        }
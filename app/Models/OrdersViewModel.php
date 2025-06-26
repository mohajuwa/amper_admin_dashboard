<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class OrdersViewModel extends Model
{
    protected $table = 'ordersview'; // View name
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'order_number',
        'user_id',
        'orders_address',
        'vendor_id',
        'vehicle_id',
        'fault_type_id',
        'order_status',
        'order_type',
        'orders_coupon_id',
        'orders_paymentmethod',
        'orders_pricedelivery',
        'order_date',
        'total_amount',
        'workshop_amount',
        'app_commission',
        'payment_status',
        'notes',
        'address_id',
        'address_user_id',
        'address_name',
        'address_street',
        'address_city',
        'address_latitude',
        'address_longitude',
        'address_status',
    ];

    // Make the model read-only
    public static function boot()
    {
        parent::boot();

        static::saving(fn() => false);
        static::creating(fn() => false);
        static::updating(fn() => false);
        static::deleting(fn() => false);
    }

    // === Custom Static Functions ===

    public static function getRecord()
    {
        $query = self::select('ordersview.*');

        if (!empty(Request::get('order_id'))) {
            $query->where('order_id', Request::get('order_id'));
        }

        if (!empty(Request::get('order_number'))) {
            $query->where('order_number', Request::get('order_number'));
        }

        if (!empty(Request::get('address_city'))) {
            $query->where('address_city', 'like', '%' . Request::get('address_city') . '%');
        }

        if (!empty(Request::get('from_date'))) {
            $query->whereDate('order_date', '>=', Request::get('from_date'));
        }

        if (!empty(Request::get('to_date'))) {
            $query->whereDate('order_date', '<=', Request::get('to_date'));
        }

        return $query->orderBy('order_id', 'desc')->paginate(20);
    }

    public static function getSingle($id)
    {
        return self::where('order_id', $id)->first();
    }

    public static function getTodayOrders()
    {
        return self::whereDate('order_date', now()->toDateString())->get();
    }

    public static function getTotalAmount()
    {
        return self::sum('total_amount');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Request;

class OrderModel extends Model
{
    use HasFactory;

    // Set the correct table name
    protected $table = 'orders';

    // Use your custom primary key
    protected $primaryKey = 'order_id';

    // Optional: If your primary key is not auto-incrementing integer
    public $incrementing = true;
    protected $keyType = 'int';

    // Fillable columns based on your schema
    protected $fillable = [
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
    ];

    // === RELATIONSHIPS ===

    public static function getRecord()
    {
        $query = self::select('orders.*');

        // Order ID search - exact match
        if (!empty(Request::get('order_id'))) {
            $query->where('order_id', Request::get('order_id'));
        }

        // Order number search - case insensitive partial match
        if (!empty(Request::get('order_number'))) {
            $orderNumber = Request::get('order_number');
            $query->where('order_number', 'like', '%' . $orderNumber . '%');
        }

        // Date range filters
        if (!empty(Request::get('from_date'))) {
            $query->whereDate('order_date', '>=', Request::get('from_date'));
        }

        if (!empty(Request::get('to_date'))) {
            $query->whereDate('order_date', '<=', Request::get('to_date'));
        }

        // Vendor name search - improved to handle JSON and case insensitivity
        if (!empty(Request::get('vendor_name'))) {
            $vendorName = Request::get('vendor_name');
            $query->whereHas('vendor', function ($q) use ($vendorName) {
                // Search in both English and Arabic within the JSON field
                // Using LOWER() for case-insensitive search
                $q->where(function ($subQuery) use ($vendorName) {
                    $subQuery->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(vendor_name, '$.en'))) LIKE LOWER(?)", ['%' . $vendorName . '%'])
                        ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(vendor_name, '$.ar'))) LIKE LOWER(?)", ['%' . $vendorName . '%']);
                });
            });
        }

        // Payment method filter
        if (!empty(Request::get('payment_method'))) {
            $query->where('orders_paymentmethod', Request::get('payment_method'));
        }

        // Order status filter
        if (!empty(Request::get('order_status'))) {
            $query->where('order_status', Request::get('order_status'));
        }

        // Amount range filters
        if (!empty(Request::get('min_amount'))) {
            $minAmount = (float) Request::get('min_amount');
            $query->where('total_amount', '>=', $minAmount);
        }

        if (!empty(Request::get('max_amount'))) {
            $maxAmount = (float) Request::get('max_amount');
            $query->where('total_amount', '<=', $maxAmount);
        }

        return $query->orderBy('order_id', 'desc')->paginate(20);
    }

    // Alternative method if you prefer to handle JSON in PHP instead of MySQL
    public static function getRecordAlternative()
    {
        $query = self::select('orders.*');

        // Order ID search - exact match
        if (!empty(Request::get('order_id'))) {
            $query->where('order_id', Request::get('order_id'));
        }

        // Order number search - case insensitive partial match
        if (!empty(Request::get('order_number'))) {
            $orderNumber = Request::get('order_number');
            $query->whereRaw('LOWER(order_number) LIKE LOWER(?)', ['%' . $orderNumber . '%']);
        }

        // Date range filters
        if (!empty(Request::get('from_date'))) {
            $query->whereDate('order_date', '>=', Request::get('from_date'));
        }

        if (!empty(Request::get('to_date'))) {
            $query->whereDate('order_date', '<=', Request::get('to_date'));
        }

        // Payment method filter
        if (!empty(Request::get('payment_method'))) {
            $query->where('orders_paymentmethod', Request::get('payment_method'));
        }

        // Order status filter
        if (!empty(Request::get('order_status'))) {
            $query->where('order_status', Request::get('order_status'));
        }

        // Amount range filters
        if (!empty(Request::get('min_amount'))) {
            $minAmount = (float) Request::get('min_amount');
            $query->where('total_amount', '>=', $minAmount);
        }

        if (!empty(Request::get('max_amount'))) {
            $maxAmount = (float) Request::get('max_amount');
            $query->where('total_amount', '<=', $maxAmount);
        }

        // Get initial results
        $results = $query->orderBy('order_id', 'desc')->get();

        // Filter by vendor name in PHP if needed
        if (!empty(Request::get('vendor_name'))) {
            $vendorName = strtolower(Request::get('vendor_name'));
            $results = $results->filter(function ($order) use ($vendorName) {
                if ($order->vendor && $order->vendor->vendor_name) {
                    $vendorNames = json_decode($order->vendor->vendor_name, true);
                    if (is_array($vendorNames)) {
                        $enName = isset($vendorNames['en']) ? strtolower($vendorNames['en']) : '';
                        $arName = isset($vendorNames['ar']) ? strtolower($vendorNames['ar']) : '';

                        return (strpos($enName, $vendorName) !== false) ||
                            (strpos($arName, $vendorName) !== false);
                    }
                }
                return false;
            });
        }

        // Manual pagination for filtered results   
        $page = Request::get('page', 1);
        $perPage = 20;
        $total = $results->count();
        $items = $results->forPage($page, $perPage);

        return new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => Request::url(),
                'pageName' => 'page',
            ]
        );
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(VendorModel::class, 'vendor_id', 'vendor_id');
    }


    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_id');
    }

    public function faultType(): BelongsTo
    {
        return $this->belongsTo(FaultTypeModel::class, 'fault_type_id');
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(CouponModel::class, 'orders_coupon_id');
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(AddressModel::class, 'orders_address');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethodModel::class, 'orders_paymentmethod');
    }

    // Example: If order has items
    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id', 'order_id');
    }

    // If order has website info
    public function websiteInfo(): HasOne
    {
        return $this->hasOne(WebsiteInfoOrder::class, 'order_id', 'order_id');
    }

    // === STATIC METHODS ===

    public static function getSingle($id)
    {
        return self::where('order_id', $id)->first();
    }

    public static function getLatestOrders($limit = 5)
    {
        return self::where('payment_status', 'paid')
            ->orderBy('order_id', 'desc')
            ->limit($limit)
            ->get();
    }

    public static function getTotalOrders()
    {
        return self::where('order_status', '!=', 5)->count();
    }

    public static function getTotalAmount()
    {
        return self::where('payment_status', 'paid')->sum('total_amount');
    }

    public static function getTodayOrders()
    {
        return self::whereDate('order_date', now()->toDateString())
            ->count();
    }

    public static function getTodayAmount()
    {
        return self::whereDate('order_date', now()->toDateString())
            ->where('payment_status', 'paid')
            ->sum('total_amount');
    }


    public static function getTotalOrdersMonth($start_date, $end_date)
    {
        return self::select('order_id ')
            ->where('payment_status', 'paid')
            ->where('order_status', '!=', 5)
            ->whereBetween('order_date', [$start_date, $end_date])
            ->count();
    }

    // In OrderModel
    public static function getTotalAmountMonth($start_date, $end_date)
    {
        return self::where('payment_status', 'paid')
            ->where('order_status', '!=', 5)
            ->whereBetween('order_date', [
                Carbon::parse($start_date)->startOfDay(),
                Carbon::parse($end_date)->endOfDay()
            ])
            ->sum('total_amount');
    }
}

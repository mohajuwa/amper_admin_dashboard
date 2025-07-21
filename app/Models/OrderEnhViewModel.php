<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
class OrderEnhViewModel extends Model
{
    protected $table = 'enhanced_orders_view';
    protected $primaryKey = 'order_id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'order_number',
        'user_id',
        'user_name',
        'orders_address',
        'vendor_id',
        'vendor_name',
        'vehicle_id',
        'make_name',
        'model_name',
        'year',
        'license_plate_number',
        'is_scheduled',
        'sub_service_ids',
        'sub_service_names',
        'services_total_price',
        'service_ids',
        'service_names',
        'fault_type_name',
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
        'address_name',
        'address_street',
        'address_city',
        'address_latitude',
        'address_longitude',
    ];

 
    public function scheduling()
    {
        // An order has one scheduling record, linked by the 'order_id' column.
        return $this->hasOne(OrderScheduling::class, 'order_id', 'order_id');
    }

    public function offers()
    {
        return $this->hasMany(OrderOffer::class, 'order_id', 'order_id');
    }

    public function activityLog()
    {
        // Order by latest first
        return $this->hasMany(OrderActivityLog::class, 'order_id', 'order_id')->latest();
    }

    public function negotiations()
    {
        return $this->hasMany(OrderNegotiation::class, 'order_id', 'order_id')->orderBy('negotiation_round');
    }

    public function customerResponses()
    {
        return $this->hasMany(CustomerResponse::class, 'order_id', 'order_id');
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

    public function items(): HasMany
    {
        return $this->hasMany(OrderItemModel::class, 'order_id', 'order_id');
    }

    public function websiteInfo(): HasOne
    {
        return $this->hasOne(WebsiteInfoOrder::class, 'order_id', 'order_id');
    }

    public static function getSingle($id)
    {
        return self::where('order_id', $id)->first();
    }

    /**
     * Helper method to safely decode JSON with fallback
     */
    private function safeJsonDecode($value, $default = [])
    {
        if (empty($value) || !is_string($value)) {
            return $default;
        }

        // First try normal JSON decode
        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        // Try to fix malformed JSON (multiple objects)
        $fixedJson = '[' . preg_replace('/}\s*,\s*{/', '},{', $value) . ']';
        $decoded = json_decode($fixedJson, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            // If it's an array of objects, merge them into one
            if (count($decoded) > 1 && is_array($decoded[0])) {
                $merged = [];
                foreach ($decoded as $item) {
                    if (is_array($item)) {
                        $merged = array_merge($merged, $item);
                    }
                }
                return $merged;
            }
            return $decoded[0] ?? $default;
        }

        // Log the error for debugging
        Log::warning("Failed to decode JSON for field", [
            'value' => $value,
            'error' => json_last_error_msg()
        ]);

        return $default;
    }

    public function getVendorNameAttribute($value)
    {
        return $this->safeJsonDecode($value, ['en' => '', 'ar' => '']);
    }

    public function getMakeNameAttribute($value)
    {
        return $this->safeJsonDecode($value, ['en' => '', 'ar' => '']);
    }

    public function getModelNameAttribute($value)
    {
        return $this->safeJsonDecode($value, ['en' => '', 'ar' => '']);
    }

    public function getLicensePlateNumberAttribute($value)
    {
        return $this->safeJsonDecode($value, ['en' => '', 'ar' => '']);
    }

    public function getFaultTypeNameAttribute($value)
    {
        return $this->safeJsonDecode($value, ['en' => '', 'ar' => '']);
    }

    public function getSubServiceNamesAttribute($value)
    {
        return $this->safeJsonDecode($value, ['en' => '', 'ar' => '']);
    }

    public function getServiceNamesAttribute($value)
    {
        return $this->safeJsonDecode($value, ['en' => '', 'ar' => '']);
    }

    /**
     * Get localized value with fallback
     */
    public function getLocalizedValue($field, $locale = 'en')
    {
        $value = $this->$field;
        if (is_array($value)) {
            return $value[$locale] ?? $value['en'] ?? '';
        }
        return $value ?? '';
    }

    /**
     * Get raw attribute value (bypasses accessors) for debugging
     */
    public function getRawAttribute($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Get all raw JSON field values for debugging
     */
    public function getRawJsonFields()
    {
        $jsonFields = [
            'vendor_name',
            'make_name', 
            'model_name',
            'license_plate_number',
            'fault_type_name',
            'sub_service_names',
            'service_names'
        ];

        $rawData = [];
        foreach ($jsonFields as $field) {
            $rawData[$field] = $this->getRawAttribute($field);
        }

        return $rawData;
    }

    /**
     * Debug method to check if JSON fields are being processed correctly
     */
    public function debugJsonFields()
    {
        $jsonFields = [
            'vendor_name',
            'make_name', 
            'model_name',
            'license_plate_number',
            'fault_type_name',
            'sub_service_names',
            'service_names'
        ];

        $debug = [];
        foreach ($jsonFields as $field) {
            $debug[$field] = [
                'raw' => $this->getRawAttribute($field),
                'processed' => $this->$field,
                'is_string' => is_string($this->getRawAttribute($field)),
                'is_array' => is_array($this->$field),
                'json_valid' => $this->getRawAttribute($field) ? json_last_error() === JSON_ERROR_NONE : false
            ];
        }

        return $debug;
    }
}
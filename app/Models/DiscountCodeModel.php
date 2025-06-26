<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // في أعلى الملف إذا لم يكن مضافًا

class DiscountCodeModel extends Model
{
    use HasFactory;

    protected $table = 'coupon';
    protected $primaryKey = 'coupon_id';

    // Add timestamps if your table has created_at and updated_at columns
    public $timestamps = true;

    protected $fillable = [
        'coupon_name',
        'coupon_count',
        'coupon_status',
        'coupon_discount',
        'coupon_expiredate',
        'used_count', // Add this if you added the used_count column
    ];

    // Add casts for proper data types
    protected $casts = [
        'coupon_discount' => 'decimal:2',
        'coupon_expiredate' => 'datetime',
        'coupon_count' => 'integer',
        'coupon_status' => 'integer',
        'used_count' => 'integer',
    ];

    public static function getRecord()
    {
        $query = self::select('coupon.*');

        // فلاتر مباشرة بالقيمة
        $filters = [
            'coupon_id' => 'coupon.coupon_id',
            'coupon_name' => 'coupon.coupon_name',
            'discount_min' => 'coupon.coupon_discount',
            'discount_max' => 'coupon.coupon_discount',
        ];

        foreach ($filters as $param => $column) {
            if (request()->filled($param)) {
                // خصم من
                if ($param == 'discount_min') {
                    $query->where($column, '>=', request()->get($param));
                }
                // خصم إلى
                elseif ($param == 'discount_max') {
                    $query->where($column, '<=', request()->get($param));
                }
                // الاسم: استخدام LIKE
                elseif ($param == 'coupon_name') {
                    $query->where($column, 'like', '%' . request()->get($param) . '%');
                }
                // عادي
                else {
                    $query->where($column, request()->get($param));
                }
            }
        }

        // فلتر الحالة
        if (request()->has('status') && request()->get('status') !== '') {
            $status = request()->get('status');

            switch ($status) {
                case 'active': // نشط + لم تنتهِ صلاحيته
                    $query->where('coupon.coupon_status', 0)
                        ->whereDate('coupon.coupon_expiredate', '>=', now());
                    break;

                case 'expired': // نشط لكن منتهي
                    $query->where('coupon.coupon_status', 0)
                        ->whereDate('coupon.coupon_expiredate', '<', now());
                    break;

                case 'inactive':
                    $query->where('coupon.coupon_status', 1);
                    break;

                case 'deleted':
                    $query->where('coupon.coupon_status', 2);
                    break;
            }
        }

        return $query->orderByDesc('coupon.coupon_id')->paginate(20);
    }


    public static function getSingle($coupon_id)
    {
        return self::find($coupon_id);
    }

    public static function checkDiscount($coupon)
    {
        return self::select('coupon.*')
            ->where('coupon.coupon_name', $coupon)
            ->where('coupon.coupon_expiredate', '>=', date('Y-m-d H:i:s')) // Fixed column name
            ->where('coupon.coupon_status', 0) // Add status check
            ->first();
    }

    // Add scope for active coupons
    public function scopeActive($query)
    {
        return $query->where('coupon_status', 0);
    }

    // Add scope for non-expired coupons
    public function scopeNotExpired($query)
    {
        return $query->where('coupon_expiredate', '>=', now());
    }

    // Add method to check if coupon is usable
    public function isUsable()
    {
        return $this->coupon_status == 0 &&
            $this->coupon_expiredate >= now() &&
            ($this->used_count ?? 0) < $this->coupon_count;
    }
}
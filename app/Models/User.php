<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Request;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * Primary key in the table
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'full_name',
        'phone',
        'verfiycode',
        'status',
        'is_delete',

        'approve',
    ];

    public function userDetail()
    {
        return $this->hasOne(UserDetail::class, 'user_id', 'user_id');
    }

    // Customer Management
    public static function getCustomer()
    {
        $query = self::query();

        if (!empty(Request::get('user_id'))) {
            $query->where('user_id', Request::get('user_id'));
        }

        if (!empty(Request::get('full_name'))) {
            $query->where('full_name', 'LIKE', '%' . Request::get('full_name') . '%');
        }

        if (!empty(Request::get('phone'))) {
            $query->where('phone', 'LIKE', '%' . Request::get('phone') . '%');
        }

        if (!empty(Request::get('status'))) {
            $query->where('status', Request::get('status'));
        }

        if (!empty(Request::get('from_date'))) {
            $query->whereDate('created_at', '>=', Request::get('from_date'));
        }

        if (!empty(Request::get('to_date'))) {
            $query->whereDate('created_at', '<=', Request::get('to_date'));
        }

        return $query->orderBy('user_id', 'desc')
            ->paginate(20);
    }

    // Single Record Fetch
    public static function getSingle($id)
    {
        return self::find($id);
    }

    // Count Active Customers
    public static function getActiveCustomers()
    {
        return self::where('status', 'active')->count();
    }
    public static function getTotalCustomers()
    {
        return self::select('user_id ')->count();
    }
    // New Customer Registrations (Today)
    public static function getTodayTotalCustomers()
    {
        return self::whereDate('created_at', today())->count();
    }

    public static function getTodayRegistrations()
    {
        return self::whereDate('created_at', today())->count();
    }
    public static function getTotalCustomersMonth($start_date, $end_date)
    {
        return self::select('user_id')
            ->whereDate('created_at', '>=', $start_date)
            ->whereDate('created_at', '<=', $end_date)
            ->count();
    }
    // Pending Approvals
    public static function getPendingApprovals()
    {
        return self::where('approve', 0)->count();
    }

    // Verify Phone Number
    public static function verifyPhone($phone, $code)
    {
        return self::where('phone', $phone)
            ->where('verfiycode', $code)
            ->first();
    }

    // Update Verification Code
    public static function updateVerificationCode($phone, $code)
    {
        return self::where('phone', $phone)
            ->update(['verfiycode' => $code]);
    }

    // Bulk Status Update
    public static function bulkStatusUpdate($status, $userIds)
    {
        return self::whereIn('user_id', $userIds)
            ->update(['status' => $status]);
    }

    // Get by Phone Number
    public static function getByPhone($phone)
    {
        return self::where('phone', $phone)->first();
    }
}
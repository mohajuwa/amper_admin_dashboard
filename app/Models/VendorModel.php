<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorModel extends Model
{
    use HasApiTokens, Notifiable, HasFactory;

    protected $table = 'vendors';
    protected $primaryKey = 'vendor_id';
    public $timestamps = false;

    protected $fillable = [
        'owner_name',
        'vendor_name',
        'vendor_type',
        'phone',
        'address',
        'password',
        'role',
        'status',
        'description',
        'registered_at'
    ];

    /**
     * Cast JSON columns to arrays automatically.
     * This tells Laravel to treat these database columns as arrays.
     */
    protected $casts = [
        'vendor_name' => 'array',
        'owner_name' => 'array',
        'description' => 'array',
        'registered_at' => 'datetime',
    ];


    /**
     * Get records with searching and filtering.
     * This method now correctly searches inside the JSON columns.
     */
    public static function getRecord()
    {
        $query = self::select('vendors.*');

        if (request()->filled('vendor_id')) {
            $query->where('vendor_id', request('vendor_id'));
        }

        // Search within the JSON 'vendor_name' column
        if (request()->filled('vendor_name')) {
            $searchTerm = '%' . request('vendor_name') . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('vendor_name->ar', 'like', $searchTerm)
                  ->orWhere('vendor_name->en', 'like', $searchTerm);
            });
        }

        if (request()->filled('vendor_type')) {
            $query->where('vendor_type', request('vendor_type'));
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        return $query->orderBy('vendor_id', 'desc')->paginate(15);
    }

    /**
     * Get a single record by its ID.
     */
    public static function getSingle($vendor_id)
    {
        return self::find($vendor_id);
    }

    // ACCESSORS TO GET CLEAN DATA //

    /**
     * Gets the Arabic vendor name from the JSON.
     * Usage in Blade: {{ $vendor->vendor_name_ar }}
     */
    public function getVendorNameArAttribute()
    {
        return $this->vendor_name['ar'] ?? 'غير متوفر';
    }

    /**
     * Gets the English vendor name from the JSON.
     * Usage in Blade: {{ $vendor->vendor_name_en }}
     */
    public function getVendorNameEnAttribute()
    {
        return $this->vendor_name['en'] ?? 'Not Available';
    }

    /**
     * Gets the Arabic owner name from the JSON.
     * Usage in Blade: {{ $vendor->owner_name_ar }}
     */
    public function getOwnerNameArAttribute()
    {
        // Fallback to English if Arabic isn't present
        return $this->owner_name['ar'] ?? $this->owner_name['en'] ?? 'غير متوفر';
    }
}
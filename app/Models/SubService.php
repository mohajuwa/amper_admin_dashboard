<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class SubService extends Model
{
    use HasFactory;

    protected $table = 'sub_services';
    protected $primaryKey = 'sub_service_id';

    protected $fillable = [
        'service_id',
        'name',
        'price',
        'status',
    ];

    // protected $casts = [
    //     'name' => 'array',
    //     'price' => 'decimal:2',
    //     'status' => 'integer',
    //     'created_at' => 'datetime',
    //     'updated_at' => 'datetime',
    // ];
    // Relationship with Service
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    // Relationship with ServiceNotes
    public function notes()
    {
        return $this->hasMany(ServiceNote::class, 'sub_service_id', 'sub_service_id');
    }
    public static function getRecord()
    {
        $query = self::select('sub_services.*', 'services.service_name as service_name')
            ->join('services', 'services.service_id', '=', 'sub_services.service_id');

        // Filter by sub_service_id
        if (request()->get('sub_service_id')) {
            $query = $query->where('sub_services.sub_service_id', request()->get('sub_service_id'));
        }

        // Filter by sub_service_name (normal or JSON)
        if (request()->get('sub_service_name')) {
            $searchTerm = request()->get('sub_service_name');
            $query = $query->where(function ($q) use ($searchTerm) {
                $q->where('sub_services.name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereRaw("JSON_EXTRACT(sub_services.name, '$.ar') LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("JSON_EXTRACT(sub_services.name, '$.en') LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        // Filter by parent service ID
        if (request()->get('service_id')) {
            $query = $query->where('sub_services.service_id', request()->get('service_id'));
        }

        // Filter by status
        if (request()->get('status') !== null && request()->get('status') !== '') {
            $query = $query->where('sub_services.status', request()->get('status'));
        }

        // Filter by from date
        if (request()->get('from_date')) {
            $query = $query->whereDate('sub_services.created_at', '>=', request()->get('from_date'));
        }

        // Optional: to_date (not in your current form but easy to add)
        if (request()->get('to_date')) {
            $query = $query->whereDate('sub_services.created_at', '<=', request()->get('to_date'));
        }

        return $query->orderBy('sub_services.sub_service_id', 'desc')
            ->paginate(50);
    }


    public function getNameForLang($lang)
    {
        $name = $this->getRawOriginal('name');

        if (is_string($name)) {
            $decoded = json_decode($name, true);
            if (is_array($decoded) && isset($decoded[$lang])) {
                return $decoded[$lang];
            }
        }

        return '';
    }

    // 2. UPDATE the getNameAttribute method in SubService.php:
    public function getNameAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                // Return based on current locale, fallback to Arabic, then English
                $locale = app()->getLocale();
                return $decoded[$locale] ?? $decoded['ar'] ?? $decoded['en'] ?? $value;
            }
        }
        return $value;
    }



    public static function getRecordByService($service_id)
    {
        return self::select('sub_services.*')
            ->where('sub_services.status', '=', 0) // Active sub-services
            ->where('sub_services.service_id', '=', $service_id)
            ->orderBy('sub_services.name', 'asc')
            ->get();
    }

    public static function getRecordActive()
    {
        return self::select('sub_services.*', 'services.service_name as service_name')
            ->join('services', 'services.service_id', '=', 'sub_services.service_id')
            ->where('sub_services.status', '=', 0)
            ->where('services.status', '=', 0)
            ->orderBy('sub_services.name', 'asc')
            ->get();
    }

    public static function getSingle($id)
    {
        return self::where('sub_service_id', $id)->first();
    }

    private static function getLocalizedName($name, $locale = 'ar')
    {
        if (is_string($name)) {
            $decoded = json_decode($name, true);
            if (is_array($decoded)) {
                return $decoded[$locale] ?? $decoded['en'] ?? $decoded['ar'] ?? 'غير محدد';
            }
            return $name;
        }

        if (is_array($name)) {
            return $name[$locale] ?? $name['en'] ?? $name['ar'] ?? 'غير محدد';
        }

        return 'غير محدد';
    }

    public static function getSingleByName($name, $service_id = null)
    {
        $query = self::where('name', 'like', '%"' . $name . '"%')
            ->where('sub_services.status', '=', 1);

        if ($service_id) {
            $query->where('service_id', $service_id);
        }

        return $query->first();
    }

    public function totalNotes()
    {
        return $this->notes()->count();
    }

    // Accessor to get name as string if it's multilingual JSON

    // Mutator to store name as JSON if needed

    public function setNameAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['name'] = json_encode($value, JSON_UNESCAPED_UNICODE);
        } else {
            $this->attributes['name'] = $value;
        }
    }

    // Get formatted price
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 2);
    }

    public function getByService(Request $request)
    {
        try {
            $serviceId = $request->input('service_id');

            if (!$serviceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Service ID is required'
                ], 400);
            }

            $subServices = SubService::where('service_id', $serviceId)
                ->where('status', 0) // Only active sub-services
                ->select('sub_service_id', 'name', 'service_id')
                ->orderBy('name')
                ->get();

            // Transform the data to ensure proper JSON structure
            $transformedSubServices = $subServices->map(function ($subService) {
                return [
                    'sub_service_id' => $subService->sub_service_id,
                    'name' => $subService->name,
                    'service_id' => $subService->service_id
                ];
            });

            return response()->json([
                'success' => true,
                'sub_services' => $transformedSubServices,
                'total' => $transformedSubServices->count()
            ]);

        } catch (\Exception $e) {
            \Log::error('Error fetching sub-services: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تحميل الخدمات الفرعية'
            ], 500);
        }
    }

    // If you want to keep the old method for backward compatibility, rename it:
    /**
     * @deprecated Use getByService instead
     */
    public function getSubCategory(Request $request)
    {
        return $this->getByService($request);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $table = 'services';
    protected $primaryKey = 'service_id';

    protected $fillable = [
        'service_name',
        'service_img',
        'status',
    ];

    protected $casts = [
        'service_name' => 'array', // Since it's stored as JSON
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getServiceImage()
    {
        if (!empty($this->service_img)) {
            return url('https://modwir.com/haytham_store/upload/categories/' . $this->service_img);
        }
        return null;
    }

    public static function getRecord()
    {
        $query = self::select('services.*'); // Get all records including deleted

        // Filter by service ID
        if (request()->get('service_id')) {
            $query = $query->where('services.service_id', request()->get('service_id'));
        }

        // Filter by service name (search in both Arabic and English names)
        if (request()->get('service_name')) {
            $searchTerm = request()->get('service_name');
            $query = $query->where(function ($q) use ($searchTerm) {
                $q->where('services.service_name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereRaw("JSON_EXTRACT(services.service_name, '$.ar') LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("JSON_EXTRACT(services.service_name, '$.en') LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        // Filter by status
        if (request()->get('status') !== null && request()->get('status') !== '') {
            $query = $query->where('services.status', request()->get('status'));
        }

        // Filter by date range
        if (request()->get('from_date')) {
            $query = $query->whereDate('services.created_at', '>=', request()->get('from_date'));
        }

        if (request()->get('to_date')) {
            $query = $query->whereDate('services.created_at', '<=', request()->get('to_date'));
        }

        return $query->orderBy('services.service_id', 'desc')
            ->paginate(15);
    }

    public static function getAllServices()
    {
        return self::select('services.*')
            ->orderBy('services.service_name', 'asc')
            ->get();
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


    public static function getSingle($id)
    {
        return self::where('service_id', $id)->first();
    }

    public static function getSingleByName($name)
    {
        return self::where('service_name', 'like', '%"' . $name . '"%')
            ->where('services.status', '=', 0) // FIXED: 0 = Active
            ->first();
    }

    // SIMPLIFIED: Accessor to get service name for display
    public function getServiceNameDisplayAttribute()
    {
        $value = $this->getAttributes()['service_name']; // Get raw value
        
        if (is_string($value) && json_decode($value)) {
            $decoded = json_decode($value, true);
            // Return Arabic first, then English, then first available
            return $decoded['ar'] ?? $decoded['en'] ?? reset($decoded);
        }
        return $value;
    }

    // SIMPLIFIED: Get specific language service name
    public function getServiceNameLang($lang = 'ar')
    {
        $value = $this->getAttributes()['service_name']; // Get raw value
        
        if (is_string($value) && json_decode($value)) {
            $decoded = json_decode($value, true);
            return $decoded[$lang] ?? '';
        }
        return $value;
    }

   
}
<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CarMakesModel extends Model
{
    use HasApiTokens, Notifiable;
    protected $table = 'car_makes';


    protected $primaryKey = 'make_id';

    protected $fillable = [
        'name',
        'logo',
        'status',
        'popularity',
    ];

    protected $casts = [
        'name' => 'array', // Since it's stored as JSON

    ];
public $timestamps = false;

    public function getCarMakeLogo()
    {
        if (!empty($this->logo)) {
            return url('https://modwir.com/haytham_store/upload/cars/' . $this->logo);
        }
        return null;
    }

    public static function getRecord()
    {
        $query = self::select('car_makes.*'); // Get all records including deleted

        // Filter by CarMake ID
        if (request()->get('make_id')) {
            $query = $query->where('car_makes.make_id', request()->get('make_id'));
        }

        // Filter by CarMake name (search in both Arabic and English names)
        if (request()->get('name')) {
            $searchTerm = request()->get('name');
            $query = $query->where(function ($q) use ($searchTerm) {
                $q->where('car_makes.name', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhereRaw("JSON_EXTRACT(car_makes.name, '$.ar') LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("JSON_EXTRACT(car_makes.name, '$.en') LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        // Filter by status
        if (request()->get('status') !== null && request()->get('status') !== '') {
            $query = $query->where('car_makes.status', request()->get('status'));
        }

        // Filter by popularity range
        if (request()->get('popularity_min') !== null && request()->get('popularity_min') !== '') {
            $query = $query->where('car_makes.popularity', '>=', request()->get('popularity_min'));
        }

        if (request()->get('popularity_max') !== null && request()->get('popularity_max') !== '') {
            $query = $query->where('car_makes.popularity', '<=', request()->get('popularity_max'));
        }

        // Filter by popularity level (predefined ranges)
        if (request()->get('popularity_level') !== null && request()->get('popularity_level') !== '') {
            $popularityLevel = request()->get('popularity_level');

            switch ($popularityLevel) {
                case 'highest': // الاعلى شهرة
                    $query = $query->where('car_makes.popularity', '>=', 90);
                    break;
                case 'very_high': // شهيرة جداً
                    $query = $query->whereBetween('car_makes.popularity', [80, 89]);
                    break;
                case 'good': // شهرة جيدة
                    $query = $query->whereBetween('car_makes.popularity', [70, 79]);
                    break;
                case 'average': // جيد
                    $query = $query->whereBetween('car_makes.popularity', [60, 69]);
                    break;
                case 'acceptable': // مقبول
                    $query = $query->whereBetween('car_makes.popularity', [40, 59]);
                    break;
                case 'low': // ضعيف
                    $query = $query->whereBetween('car_makes.popularity', [1, 39]);
                    break;
                case 'undefined': // غير محدد
                    $query = $query->where('car_makes.popularity', 0);
                    break;
            }
        }

        return $query->orderBy('car_makes.popularity', 'desc')
            ->paginate(15);
    }
// Add this to your CarModelsModel class
public function carMake()
{
    return $this->belongsTo(CarMakesModel::class, 'make_id', 'make_id');
}
    public static function getAllCarMakes()
    {
        return self::select('car_makes.*')
            ->orderBy('popularity', 'desc')
            ->get();
    }




    public static function getSingle($id)
    {
        return self::where('make_id', $id)->first();
    }

    public static function getSingleByName($name)
    {
        return self::where('name', 'like', '%"' . $name . '"%')
            ->first();
    }

    // SIMPLIFIED: Accessor to get CarMake name for display
    public function getCarMakeNameDisplayAttribute()
    {
        $value = $this->getAttributes()['name']; // Get raw value

        if (is_string($value) && json_decode($value)) {
            $decoded = json_decode($value, true);
            // Return Arabic first, then English, then first available
            return $decoded['ar'] ?? $decoded['en'] ?? reset($decoded);
        }
        return $value;
    }

    // SIMPLIFIED: Get specific language CarMake name
    public function getCarMakeNameLang($lang = 'ar')
    {
        $value = $this->getAttributes()['name']; // Get raw value

        if (is_string($value) && json_decode($value)) {
            $decoded = json_decode($value, true);
            return $decoded[$lang] ?? '';
        }
        return $value;
    }


}
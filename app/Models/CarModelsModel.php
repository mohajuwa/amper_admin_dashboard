<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CarModelsModel extends Model
{
    use HasApiTokens, Notifiable;

    protected $table = 'car_models';
    protected $primaryKey = 'model_id';

    protected $fillable = [
        'make_id',
        'name',
        'status',
    ];

    protected $casts = [
        'name' => 'array',
        'status' => 'integer',
    ];

    public $timestamps = false;

    // Relationship with CarMakesModel
    public function carMake()
    {
        return $this->belongsTo(CarMakesModel::class, 'make_id', 'make_id');
    }

    // Access car make logo through relationship
    public function getCarMakeLogo()
    {
        return $this->carMake ? $this->carMake->getCarMakeLogo() : null;
    }

    // Access car make name through relationship
    public function getCarMakeName()
    {
        return $this->carMake ? $this->carMake->getCarMakeNameDisplayAttribute() : '';
    }

    public static function getRecord()
    {
        $query = self::with('carMake')
            ->join('car_makes', 'car_makes.make_id', '=', 'car_models.make_id')
            ->select('car_models.*', 'car_makes.name as car_make_name');

        // Filter by model_id
        if (request()->get('model_id')) {
            $query->where('car_models.model_id', request()->get('model_id'));
        }

        // Filter by model_name (normal or JSON)
        if (request()->get('car_model_name')) {
            $searchTerm = mb_strtolower(request()->get('car_model_name'));

            $query->where(function ($q) use ($searchTerm) {
                $q->whereRaw("LOWER(car_models.name) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(car_models.name, '$.ar'))) LIKE ?", ['%' . $searchTerm . '%'])
                    ->orWhereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(car_models.name, '$.en'))) LIKE ?", ['%' . $searchTerm . '%']);
            });
        }

        // Filter by make_id
        if (request()->get('make_id')) {
            $query->where('car_models.make_id', request()->get('make_id'));
        }

        // Filter by status
        if (request()->get('status') !== null && request()->get('status') !== '') {
            $query->where('car_models.status', request()->get('status'));
        }

        return $query->orderBy('car_makes.popularity', 'desc')
            ->paginate(20);
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

    public function getNameAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_array($decoded)) {
                $locale = app()->getLocale();
                return $decoded[$locale] ?? $decoded['ar'] ?? $decoded['en'] ?? $value;
            }
        }
        return $value;
    }

    public static function getRecordByService($make_id)
    {
        return self::select('car_models.*')
            ->where('car_models.status', '=', 1)
            ->where('car_models.make_id', '=', $make_id)
            ->orderBy('car_models.name', 'asc')
            ->get();
    }

    public static function getRecordActive()
    {
        return self::select('car_models.*', 'car_makes.name as name')
            ->join('car_makes', 'car_makes.make_id', '=', 'car_models.make_id')
            ->where('car_models.status', '=', 1)
            ->where('car_makes.status', '=', 1)
            ->orderBy('car_models.name', 'asc')
            ->get();
    }

    public static function getSingle($id)
    {
        return self::where('model_id', $id)->first();
    }

    public static function getSingleByName($name, $make_id = null)
    {
        $query = self::where('name', 'like', '%"' . $name . '"%')
            ->where('car_models.status', '=', 1);

        if ($make_id) {
            $query->where('make_id', $make_id);
        }

        return $query->first();
    }

    public function setNameAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['name'] = json_encode($value);
        } else {
            $this->attributes['name'] = $value;
        }
    }

    public function getCarModelNameDisplayAttribute()
    {
        $value = $this->getAttributes()['name'];

        if (is_string($value) && json_decode($value)) {
            $decoded = json_decode($value, true);
            return $decoded['ar'] ?? $decoded['en'] ?? reset($decoded);
        }
        return $value;
    }

    public function getCarModelNameLang($lang = 'ar')
    {
        $value = $this->getAttributes()['name'];

        if (is_string($value) && json_decode($value)) {
            $decoded = json_decode($value, true);
            return $decoded[$lang] ?? '';
        }
        return $value;
    }
}
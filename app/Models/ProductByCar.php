<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductByCar extends Model
{
    use HasFactory;

    protected $table = 'product_by_car';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'model_id',
        'service_id',
        'sub_service_id',
        'year',
        'status',
    ];

    protected $casts = [
        'model_id' => 'integer',
        'service_id' => 'integer',
        'sub_service_id' => 'integer',
        'year' => 'integer',
        'status' => 'integer',

    ];

    public $timestamps = false;


    // Constants for status
    const STATUS_ACTIVE = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_DELETED = 2;

    // Relationships



    public static function getSingle($id)
    {

        return self::with(['carModel']) // Load the carModel relationship

            ->where('product_id', $id)

            ->first();

    }



    public static function getRecord()
    {

        $query = self::select(

            'product_by_car.*',

            'services.service_name',

            'sub_services.name as sub_service_name',

            'car_models.name',

            'car_makes.name as car_make_name',

            'car_makes.logo as car_make_logo'

        )

            ->leftJoin('services', 'services.service_id', '=', 'product_by_car.service_id')

            ->leftJoin('sub_services', 'sub_services.sub_service_id', '=', 'product_by_car.sub_service_id')

            ->leftJoin('car_models', 'car_models.model_id', '=', 'product_by_car.model_id')

            ->leftJoin('car_makes', 'car_makes.make_id', '=', 'car_models.make_id');



        // Apply filters

        $filters = [

            'product_id' => 'product_by_car.product_id',

            'service_id' => 'product_by_car.service_id',

            'sub_service_id' => 'product_by_car.sub_service_id',

            'model_id' => 'product_by_car.model_id',

            'year' => 'product_by_car.year'

        ];



        foreach ($filters as $param => $column) {

            if (request()->filled($param)) {

                $query->where($column, request()->get($param));

            }

        }



        // Status filter (including 0 value)

        if (request()->has('status') && request()->get('status') !== '') {

            $query->where('product_by_car.status', request()->get('status'));

        }



        // Date filters

        if (request()->filled('from_date')) {

            $query->whereDate('product_by_car.created_at', '>=', request()->get('from_date'));

        }



        if (request()->filled('to_date')) {

            $query->whereDate('product_by_car.created_at', '<=', request()->get('to_date'));

        }



        return $query->orderBy('product_by_car.product_id', 'desc')->paginate(50);

    }



    public static function getYearsRange()
    {

        $currentYear = date('Y');

        $startYear = 1981;

        $years = [];



        for ($year = $currentYear; $year >= $startYear; $year--) {

            $years[] = $year;

        }



        return $years;


    }
    public function carModel()
    {
        return $this->belongsTo(CarModelsModel::class, 'model_id', 'model_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function subService()
    {
        return $this->belongsTo(SubService::class, 'sub_service_id', 'sub_service_id');
    }


    // Helper method to get localized name from JSON
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
    public static function getRecordActive()
    {
        return self::select(
            'product_by_car.*',
            'services.service_name',
            'sub_services.name as sub_service_name',
            'car_models.name'
        )
            ->leftJoin('services', 'services.service_id', '=', 'product_by_car.service_id')
            ->leftJoin('sub_services', 'sub_services.sub_service_id', '=', 'product_by_car.sub_service_id')
            ->leftJoin('car_models', 'car_models.model_id', '=', 'product_by_car.model_id')
            ->where('product_by_car.status', self::STATUS_ACTIVE)
            ->where('services.status', self::STATUS_ACTIVE)
            ->orderBy('product_by_car.year', 'desc')
            ->get();
    }

    public static function getRecordByModel($model_id)
    {
        return self::where('status', self::STATUS_ACTIVE)
            ->where('model_id', $model_id)
            ->orderBy('year', 'desc')
            ->get();
    }



    // Accessors for display
    public function getFormattedYearAttribute()
    {
        return $this->year ? $this->year . ' م' : 'غير محدد';
    }

    public function getStatusTextAttribute()
    {
        $statusTexts = [
            self::STATUS_ACTIVE => 'نشط',
            self::STATUS_INACTIVE => 'غير نشط',
            self::STATUS_DELETED => 'محذوف'
        ];

        return $statusTexts[$this->status] ?? 'غير معروف';
    }

    public function getServiceNameDisplayAttribute()
    {
        if ($this->service && $this->service->service_name) {
            return self::getLocalizedName($this->service->service_name);
        }
        return 'غير محدد';
    }

    public function getSubServiceNameDisplayAttribute()
    {
        if ($this->subService && $this->subService->name) {
            return self::getLocalizedName($this->subService->name);
        }
        return 'غير محدد';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeInactive($query)
    {
        return $query->where('status', self::STATUS_INACTIVE);
    }

    public function scopeDeleted($query)
    {
        return $query->where('status', self::STATUS_DELETED);
    }
}
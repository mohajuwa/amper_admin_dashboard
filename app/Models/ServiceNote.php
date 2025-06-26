<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceNote extends Model
{
    use HasFactory;

    protected $table = 'service_notes';
    protected $primaryKey = 'note_id';
    
    // Disable updated_at since table doesn't have it
    public $timestamps = false;
    
    protected $fillable = [
        'sub_service_id',
        'content',
    ];

    protected $casts = [
        'content' => 'array', // Since it's stored as JSON
        'created_at' => 'datetime',
    ];

    // Relationship with SubService
    public function subService()
    {
        return $this->belongsTo(SubService::class, 'sub_service_id', 'sub_service_id');
    }

    public static function getRecord()
    {
        return self::select('service_notes.*', 'sub_services.name as sub_service_name')
            ->join('sub_services', 'sub_services.sub_service_id', '=', 'service_notes.sub_service_id')
            ->orderBy('service_notes.note_id', 'desc')
            ->paginate(20);
    }

    public static function getRecordBySubService($sub_service_id)
    {
        return self::select('service_notes.*')
            ->where('service_notes.sub_service_id', '=', $sub_service_id)
            ->orderBy('service_notes.created_at', 'desc')
            ->get();
    }

    public static function getSingle($id)
    {
        return self::where('note_id', $id)->first();
    }

    // Accessor to get content as string if it's multilingual JSON
    public function getContentAttribute($value)
    {
        if (is_string($value) && json_decode($value)) {
            $decoded = json_decode($value, true);
            // Return default language or first available
            return $decoded[app()->getLocale()] ?? reset($decoded);
        }
        return $value;
    }

    // Mutator to store content as JSON if needed
    public function setContentAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['content'] = json_encode($value);
        } else {
            $this->attributes['content'] = $value;
        }
    }

    // Override the create method to set created_at manually
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->created_at = now();
        });
    }
}
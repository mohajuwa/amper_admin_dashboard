<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class NotificationModel extends Model
{
    protected $table = 'notification';
    protected $primaryKey = 'notification_id';
    public $timestamps = false;

    protected $fillable = [
        'notification_title',
        'notification_body',
        'notification_userid',
        'notification_read',
        'notification_datetime'
    ];

    protected $casts = [
        'notification_datetime' => 'datetime',
    ];

    // Safe JSON decode
    private function safeJsonDecode($value, $default = ['en' => '', 'ar' => ''])
    {
        if (empty($value) || !is_string($value))
            return $default;

        $decoded = json_decode($value, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return $decoded;
        }

        Log::warning('Failed to decode JSON in Notification model', [
            'value' => $value,
            'error' => json_last_error_msg()
        ]);

        return $default;
    }

    // Accessors
    public function getNotificationTitleAttribute($value)
    {
        return $this->safeJsonDecode($value);
    }

    public function getNotificationBodyAttribute($value)
    {
        return $this->safeJsonDecode($value);
    }

    // Localized value accessors (optional)
    public function getLocalized($field, $locale = 'en')
    {
        $value = $this->$field;
        return $value[$locale] ?? $value['en'] ?? '';
    }

    // Example: get title in English
    public function getTitleEn()
    {
        return $this->getLocalized('notification_title', 'en');
    }

    public function getTitleAr()
    {
        return $this->getLocalized('notification_title', 'ar');
    }

    // Fixed getRecord method - removed incomplete where clause
    public static function getRecord()
    {
        $query = self::select("notification.*");
        
        // Apply search filters if they exist
        if (Request::get('notification_id')) {
            $query->where('notification_id', 'like', '%' . Request::get('notification_id') . '%');
        }
        
        if (Request::get('title')) {
            $query->where(function($q) {
                $searchTerm = '%' . Request::get('title') . '%';
                $q->where('notification_title', 'like', $searchTerm);
            });
        }
        
        if (Request::get('read_status') !== null && Request::get('read_status') !== '') {
            $query->where('notification_read', Request::get('read_status'));
        }
        
        if (Request::get('from_date')) {
            $query->whereDate('notification_datetime', '>=', Request::get('from_date'));
        }
        
        if (Request::get('to_date')) {
            $query->whereDate('notification_datetime', '<=', Request::get('to_date'));
        }
        
        return $query->orderBy('notification_id', 'desc')->paginate(15);
    }

    public static function getUnreadNotifications()
    {
        return self::where('notification_read', 0)
            ->where('notification_userid', '!=', 0)
            ->orderBy('notification_id', 'desc')
            ->get();
    }

    // Method to mark notification as read
    public static function markAsRead($notification_id)
    {
        return self::where('notification_id', $notification_id)
            ->update(['notification_read' => 1]);
    }

    // Get total unread count
    public static function getUnreadCount()
    {
        return self::where('notification_read', 0)->count();
    }
}
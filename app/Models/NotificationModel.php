<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
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
        'notification_title' => 'array',
        'notification_body' => 'array',
    ];

    /**
     * Accessor for a fully processed, readable title in Arabic.
     */
    public function getCleanTitleAttribute(): string
    {
        return $this->getCleanLocalizedValue('notification_title');
    }

    /**
     * Accessor for a fully processed, readable body in Arabic.
     */
    public function getCleanBodyAttribute(): string
    {
        return $this->getCleanLocalizedValue('notification_body');
    }

    /**
     * Private helper to get and clean a localized string.
     */
    private function getCleanLocalizedValue(string $attribute): string
    {
        $data = $this->{$attribute} ?? ['ar' => '', 'en' => ''];
        $initialString = $data['ar'] ?? $data['en'] ?? '';

        if (!is_string($initialString) || empty($initialString)) {
            return 'غير متوفر';
        }

        // CORRECTED: This robust pattern handles whitespace variations.
        $pattern = '~\{         # Match the opening brace
                     \s* # Allow whitespace
                     "en"        # Match "en"
                     \s*:\s* # Allow whitespace around the colon
                     ".*?"       # Match the English value
                     \s*,\s* # Allow whitespace around the comma
                     "ar"        # Match "ar"
                     \s*:\s* # Allow whitespace around the colon
                     ".*?"       # Match the Arabic value
                     \s* # Allow whitespace
                     \}          # Match the closing brace
                     ~x';

        $finalString = preg_replace_callback(
            $pattern,
            function ($matches) {
                $nestedData = json_decode($matches[0], true);
                return $nestedData['ar'] ?? $matches[0];
            },
            $initialString
        );

        return $finalString;
    }

    // --- Your other existing methods below ---

    public static function getRecord()
    {
        $query = self::select("notification.*");
        
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

    public static function markAsRead($notification_id)
    {
        return self::where('notification_id', $notification_id)
            ->update(['notification_read' => 1]);
    }

    public static function getUnreadCount()
    {
        return self::where('notification_read', 0)->count();
    }
}
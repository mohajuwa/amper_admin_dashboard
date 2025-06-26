<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Request; // تأكد من استيراده

class Admin extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'admins';

    protected $fillable = ['full_name', 'email', 'password', 'role', 'status', 'is_delete'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];




    public static function getAdmin()
    {
        $query = self::query();

        if (!empty(Request::get('full_name'))) {
            $query->where('full_name', 'LIKE', '%' . Request::get('full_name') . '%');
        }

        if (!empty(Request::get('email'))) {
            $query->where('email', 'LIKE', '%' . Request::get('email') . '%');
        }
          if (!empty(Request::get('role'))) {
            $query->where('role', 'LIKE',  Request::get('role') );
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

        return $query->where('is_delete', 0)
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    public static function checkEmail($email)
    {
        return self::select('admins.*')
            ->where('is_delete', '=', 0)

            ->where('status', '==', 'active')
            ->where('email', '=', $email)
            ->first();
    }
    public static function getSingle($id)
    {
        return self::find($id)
        // ->where('role', '!=', 'super_admin')
        ;
    }
}

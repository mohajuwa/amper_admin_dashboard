<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class VendorModel extends Authenticatable
{
    use HasApiTokens, Notifiable;
    protected $table = 'vendors';

    protected $fillable = ['owner_name', 'vendor_name', 'phone', 'address', 'password', 'role', 'status','description','registered_at'];

    protected $hidden = ['password'];

    protected $casts = [
        'password' => 'hashed',
    ];


    public static function getRecord()
    {
       return self::select('*')->get();
    }

    public static function checkEmail($email)
    {
        return self::select('vendors.*')
            ->where('status', '==', 'active')
            ->where('email', '=', $email)
            ->first();
    }
    public static function getSingle($id)
    {
        return self::find($id);
    }
}

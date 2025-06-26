<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMTPModel extends Model
{
    use HasFactory;
    protected $table = 'smtp';

    protected $fillable = [

        'name',
        'mail_mailer',
        'mail_host',
        'mail_port',
        'mail_username',
        'mail_password',
        'maill_enqryption',
        'mail_from_address',
        'created_at',
        'updated_at',

    ];

    public static function getRecord()
    {
        return self::select('smtp.*')->get();
    }
    public static function getSingle()
    {
        return self::find(1);
    }

   
    
}

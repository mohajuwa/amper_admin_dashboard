<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethodModel extends Model
{
    use HasFactory;
    protected $table = 'payments';

    protected $fillable = [
        'payment_id ',
        'order_id',
        'amount',
        'payment_method',
        'payment_date',
        'payment_status',
       

    ];

    public static function getRecord()
    {
        return self::select('payments.*')->get();
    }
    public static function getSingle($id)
    {
        return self::find($id);
    }
  

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderActivityLog extends Model
{
    use HasFactory;
    protected $table = 'order_activity_log';

    protected $primaryKey = 'activity_id';
    protected $guarded = [];
    const UPDATED_AT = null;

    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id', 'order_id');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderScheduling extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'order_scheduling';

    /**
     * The primary key for the model.
     * @var string
     */
    protected $primaryKey = 'scheduling_id';

    /**
     * The attributes that should be cast.
     * This automatically converts the scheduled_datetime column
     * from a string in the database to a Carbon date object.
     *
     * @var array
     */
    protected $casts = [
        'scheduled_datetime' => 'datetime',
    ];

    /**
     * Defines the relationship back to the Order.
     */
    public function order()
    {
        return $this->belongsTo(OrderModel::class, 'order_id');
    }
}
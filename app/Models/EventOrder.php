<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventOrder extends Model
{
    protected $table = 'event_orders';

    protected $fillable = [
        'user_name',
        'email',
        'mobile',
        'year',
        'jnv',
        'pass_name',
        'event_id',
        'pass_id',
        'qty',
        'amount',
        'merchant_transaction_id',
        'payment_proofs',
    ];
}

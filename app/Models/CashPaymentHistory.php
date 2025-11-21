<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CashPaymentHistory extends Model
{
    protected $table = 'cash_payments_history';

    protected $guarded = [];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

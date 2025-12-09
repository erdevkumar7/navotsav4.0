<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDonation extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'emailid',
        'phone',
        'amount',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature'
    ];
}

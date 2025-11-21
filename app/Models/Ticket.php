<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Ticket extends Model
{
    protected $fillable = [
        'event_id',
        'user_id',
        'seller_id',
        'ticket_number',
        'price',
        'reserved_until',
        'status',
        'payment_method',
        'package_id',
        'qr_code_data'
    ];

    protected function casts(): array
    {
        return [
            'qr_code_data' => 'array',
        ];
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id'); // seller_id points to users table
    }

    public function showTicket()
    {
        if ($this->qr_code_data) {
            return QrCode::size(300)->generate($this->qr_code_data);
        }
        return null;
    }

    public function pricePackage()
    {
        return $this->belongsTo(MultiplePrice::class, 'package_id');
    }
}

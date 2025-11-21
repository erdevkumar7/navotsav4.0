<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RaffleWinner extends Model
{
    protected $fillable = ['event_id', 'user_id', 'ticket_id', 'winning_price', 'ticket_number'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}

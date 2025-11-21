<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavouriteEvent extends Model
{
    protected $fillable = ['event_id', 'user_id'];

    function event()
    {
        return $this->belongsTo(Event::class);
    }
}

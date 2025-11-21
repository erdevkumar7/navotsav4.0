<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClaimRequest extends Model
{
    protected $guarded = [];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    function event()
    {
        return $this->belongsTo(Event::class);
    }
}

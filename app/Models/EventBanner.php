<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventBanner extends Model
{
    protected $fillable = ['banner', 'event_id'];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}

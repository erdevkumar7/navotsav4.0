<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'user_id')->select('id', 'name');
    }
}

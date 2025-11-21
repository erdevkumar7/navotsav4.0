<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = ['channel', 'recipient', 'purpose', 'code', 'expires_at', 'consumed_at', 'ip'];
    protected $casts = ['expires_at' => 'datetime', 'consumed_at' => 'datetime'];

    public function scopeValid($q)
    {
        return $q->whereNull('consumed_at')->where('expires_at', '>', now());
    }
}

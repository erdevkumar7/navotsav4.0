<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($event) {
            if (!$event->contest_no) {
                $event->contest_no = strtoupper(Str::random(4));
                $event->saveQuietly(); // avoid recursion
            }
        });
    }

    protected $fillable = [
        'title',
        'category_id',
        'location',
        'description',
        'ticket_price',
        'ticket_quantity',
        'winner_type',
        'multiple_price',
        'start_date',
        'end_date',
        'draw_time',
        'cause',
        'banner',
        'rules',
        'max_tickets_per_user',
        'created_by',
        'is_publish',
        'status',
        'contest_no',
        'visiblity',
        'is_finalized'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function banners()
    {
        return $this->hasMany(EventBanner::class);
    }

    public function category()
    {
        return $this->belongsTo(EventCategory::class, 'category_id');
    }

    public function multiplePrices()
    {
        return $this->hasMany(MultiplePrice::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by')->select('name');
    }

    public function totalRevenue()
    {
        return Ticket::where('event_id', $this->id)->where('status', 'paid')->sum('price');
    }

    public function totalParticipants()
    {
        $webapp =  Ticket::where('event_id', $this->id)->where('status', 'paid')->whereNull('seller_id')->groupBy('user_id')->count();
        $pos = Ticket::where('event_id', $this->id)->where('status', 'paid')->whereNotNull('seller_id')->count();

        return ($webapp + $pos);
    }

    public function totalSold()
    {
        return Ticket::where('event_id', $this->id)->where('status', 'paid')->count();
    }
}

<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_type',
        'name',
        'email',
        'password',
        'phone',
        'dob',
        'is_age_verified',
        'avatar_url',
        'preferred_language',
        'social_provider',
        'status',
        'is_verified',
        'remember_token',
        'is_notify'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'dob' => 'date',
            'is_age_verified' => 'boolean',
            'notification_settings' => 'array',
            'is_notify' => 'boolean'
        ];
    }


    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => empty($this->attributes['avatar_url'] ?? null)
                ? asset('assets/images/users/avatar-1.jpg')
                : Storage::disk('public')->url($this->attributes['avatar_url'])
        );
    }

    public function device()
    {
        return $this->hasOne(UserDevice::class);
    }
}

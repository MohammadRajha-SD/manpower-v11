<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordCustom;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    use ImageHandler;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class, 'provider_user');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function addresses()
    {
        return $this->hasMany(UserAddress::class);
    }

    
public function sendPasswordResetNotification($token)
{
    $this->notify(new ResetPasswordCustom($token));
}
}

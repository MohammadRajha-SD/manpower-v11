<?php

namespace App\Models;

use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Provider extends Authenticatable
{
    use HasFactory, HasTranslations;
    use HasApiTokens, Notifiable;
    use ImageHandler;
    
    protected $guarded = [];
    protected $guard = 'provider';

    public $translatable = ['name', 'description'];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function providerType()
    {
        return $this->belongsTo(ProviderType::class, 'provider_type_id');
    }

    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'provider_tax');
    }

    public function addresses()
    {
        return $this->belongsToMany(Address::class, 'provider_address');
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'provider_user');
    }

    public function schedules()
    {
        return $this->hasMany(ProviderSchedule::class);
    }
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'discountable');
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class, 'provider_id');
    }
    public function services()
    {
        return $this->hasMany(Service::class, 'provider_id');
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\ImageHandler;

class Service extends Model
{
    use HasFactory, HasTranslations;
    use ImageHandler;

    protected $guarded = [];

    public $translatable = ['name', 'description'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'service_category', 'service_id', 'category_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function reviews()
    {
        return $this->hasMany(ServiceReview::class, 'service_id');
    }

    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'discountable');
    }


    public function discountables()
    {
        return $this->morphMany('App\Models\Discountable', 'discountable');
    }


    public function getPrice(): float
    {
        return $this->discount_price > 0 ? $this->discount_price : $this->price;
    }
}

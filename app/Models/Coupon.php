<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Coupon extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'code',
        'discount',
        'discount_type',
        'expires_at',
        'enabled',
        'description',
    ];

    public $translatable = ['description'];

    public function discountables()
    {
        return $this->hasMany(Discountable::class, 'coupon_id');
    }

    public function services()
    {
        return $this->morphedByMany(Service::class, 'discountable');
    }

    public function categories()
    {
        return $this->morphedByMany(Category::class, 'discountable');
    }

    public function providers()
    {
        return $this->morphedByMany(Provider::class, 'discountable');
    }
}

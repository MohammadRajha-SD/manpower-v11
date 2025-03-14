<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Pack extends Model
{
    use HasFactory;
    use HasTranslations;
    protected $fillable = [
        'stripe_plan_id',
        'type',
        'price',
        'text',
        'short_description',
        'description',
        'number_of_months',
        'number_of_ads',
        'number_of_subcategories',
        'not_in_featured_services',
        'not_on_image_slider',
    ];

    public $translatable = [
        'text',
        'short_description',
        'description',
        'type',
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'pack_id');
    }
}

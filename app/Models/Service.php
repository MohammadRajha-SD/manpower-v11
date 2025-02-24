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

    protected $fillable = [
        'category_id',
        'name',
        'discount_price',
        'price',
        'price_unit',
        'quantity_unit',
        'duration',
        'description',
        'featured',
        'enable_booking',
        'available',
        'provider_id'
    ];

    public $translatable = ['name', 'description'];

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
}

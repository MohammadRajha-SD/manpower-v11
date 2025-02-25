<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;
use App\Traits\ImageHandler;

class Category extends Model
{
    use HasFactory;
    use ImageHandler;
    use HasTranslations;

    protected $fillable = [
        'name',
        'desc',
        'color',
        'order',
        'featured',
        'parent_id',
    ];

    public $translatable = ['name', 'desc'];

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }
    public function coupons()
    {
        return $this->morphToMany(Coupon::class, 'discountable');
    }
}

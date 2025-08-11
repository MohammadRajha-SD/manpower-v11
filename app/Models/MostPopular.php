<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MostPopular extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'desc',
        'category_id',
    ];

    public $translatable = [
        'name',
        'desc',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

<?php

namespace App\Models;

use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Slide extends Model
{
    use HasFactory;
    use ImageHandler;
    use HasTranslations;

    protected $fillable = [
        'text',
        'desc',
        'status',
    ];

    public $translatable = ['text', 'desc'];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

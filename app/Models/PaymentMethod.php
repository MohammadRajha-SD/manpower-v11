<?php

namespace App\Models;

use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasTranslations;
    use ImageHandler;
    use HasFactory;
    protected $guarded = [];
    public $translatable = ['name', 'description'];

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

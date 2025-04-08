<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $translatable = ['title', 'desc'];
    
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }
}

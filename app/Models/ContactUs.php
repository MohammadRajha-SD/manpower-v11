<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactUs extends Model
{
    use HasFactory;

    protected $guareded = [];
    
    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function service(){
        return $this->belongsTo(Service::class);
    }
}

<?php

namespace App\Models;

use App\Traits\ImageHandler;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use ImageHandler;
    use HasFactory;
    
    protected $guarded = [];
    
    public function imageable(){
        return $this->morphTo();
    }
}

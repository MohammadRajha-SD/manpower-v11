<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderPayout extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}

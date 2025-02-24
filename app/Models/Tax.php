<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;

    public function providers()
{
    return $this->belongsToMany(Provider::class, 'provider_tax');
}

}

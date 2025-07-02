<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'address',
        'service_charge',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

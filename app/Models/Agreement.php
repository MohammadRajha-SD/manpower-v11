<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['plan', 'prequest'];

    public function plan()
    {
        return $this->belongsTo(Pack::class);
    }

    public function prequest()
    {
        return $this->belongsTo(ProviderRequest::class, 'provider_request_id');
    }
}

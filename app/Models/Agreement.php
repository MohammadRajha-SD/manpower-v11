<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Agreement extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['plan', 'prequest', 'provider'];

    public function plan()
    {
        return $this->belongsTo(Pack::class);
    }

    public function prequest()
    {
        return $this->belongsTo(ProviderRequest::class, 'provider_request_id');
    }

    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

      protected static function booted(): void
    {
        static::creating(function ($request) {
            $request->uid = (string) Str::uuid();
        });
    }
}

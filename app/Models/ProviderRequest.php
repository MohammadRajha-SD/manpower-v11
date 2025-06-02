<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ProviderRequest extends Model
{
    use HasFactory;

    protected $table = 'provider_requests';
    protected $guarded = [];

    protected static function booted(): void
    {
        static::creating(function ($request) {
            $request->uid = (string) Str::uuid();
        });
    }

    public function agreement()
    {
        return $this->hasOne(Agreement::class);
    }
}
